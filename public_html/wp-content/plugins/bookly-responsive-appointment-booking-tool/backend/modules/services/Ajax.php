<?php
namespace Bookly\Backend\Modules\Services;

use Bookly\Backend\Components\Notices\Limitation;
use Bookly\Backend\Components\Dialogs\Service\Edit\Forms;
use Bookly\Backend\Modules\Appointments;
use Bookly\Lib;

/**
 * Class Ajax
 * @package Bookly\Backend\Modules\Services
 */
class Ajax extends Page
{
    /**
     * Get services data for data tables
     */
    public static function getServices()
    {
        $columns = self::parameter( 'columns' );
        $order   = self::parameter( 'order', array() );
        $filter  = self::parameter( 'filter' );
        $limits  = array(
            'length' => self::parameter( 'length' ),
            'start'  => self::parameter( 'start' ),
        );

        $query = Lib\Entities\Service::query( 's' )
            ->select( 's.*, c.name AS category_name' )
            ->leftJoin( 'Category', 'c', 'c.id = s.category_id' )
            ->whereIn( 's.type', array_keys( Proxy\Shared::prepareServiceTypes( array( Lib\Entities\Service::TYPE_SIMPLE => Lib\Entities\Service::TYPE_SIMPLE ) ) ) );

        foreach ( $order as $sort_by ) {
            $query->sortBy( str_replace( '.', '_', $columns[ $sort_by['column'] ]['data'] ) )
                  ->order( $sort_by['dir'] == 'desc' ? Lib\Query::ORDER_DESCENDING : Lib\Query::ORDER_ASCENDING );
        }

        $total = $query->count();

        if ( $filter['category'] != '' ) {
            $query->where( 's.category_id', $filter['category'] );
        }

        if ( $filter['search'] != '' ) {
            $fields = array();
            foreach ( $columns as $column ) {
                switch ( $column['data'] ) {
                    case 'category_name':
                        $fields[] = 'c.name';
                        break;
                    case 'id':
                    case 'title':
                        $fields[] = 's.' . $column['data'];
                        break;
                }
            }

            $search_columns = array();
            foreach ( $fields as $field ) {
                $search_columns[] = $field . ' LIKE "%%%s%"';
            }
            if ( ! empty( $search_columns ) ) {
                $query->whereRaw( implode( ' OR ', $search_columns ), array_fill( 0, count( $search_columns ), $filter['search'] ) );
            }
        }

        $filtered = $query->count();

        if ( ! empty( $limits ) ) {
            $query->limit( $limits['length'] )->offset( $limits['start'] );
        }

        $type_icons = Proxy\Shared::prepareServiceIcons( array( Lib\Entities\Service::TYPE_SIMPLE => 'far fa-calendar-check' ) );

        $data = array();
        foreach ( $query->fetchArray() as $service ) {
            $sub_services_count = count( Lib\Entities\Service::find( $service['id'] )->getSubServices() );
            $data[] = array(
                'id'            => $service['id'],
                'title'         => $service['title'],
                'position'      => sprintf( '%05d-%05d', $service['position'], $service['id'] ),
                'category_name' => $service['category_name'],
                'colors'        => Proxy\Shared::prepareServiceColors( array_fill( 0, 3, $service['color'] ), $service['id'], $service['type'] ),
                'type'          => ucfirst( $service['type'] ),
                'type_icon'     => $type_icons[ $service['type'] ],
                'price'         => Lib\Utils\Price::format( $service['price'] ),
                'duration'      => in_array( $service['type'], array( Lib\Entities\Service::TYPE_COLLABORATIVE, Lib\Entities\Service::TYPE_COMPOUND ) )
                    ? sprintf( _n( '%d service', '%d services', $sub_services_count, 'bookly' ), $sub_services_count )
                    : Lib\Utils\DateTime::secondsToInterval( $service['duration'] ),
                'online_meetings' => $service['online_meetings'],
            );
        }

        unset( $filter['search'] );

        Lib\Utils\Tables::updateSettings( 'services', $columns, $order, $filter );

        wp_send_json( array(
            'draw'            => ( int ) self::parameter( 'draw' ),
            'data'            => $data,
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
        ) );
    }

    /**
     * Update services position.
     */
    public static function updateServicesPosition()
    {
        $services_sorts = self::parameter( 'positions' );
        foreach ( $services_sorts as $position => $service_id ) {
            $services_sort = new Lib\Entities\Service();
            $services_sort->load( $service_id );
            $services_sort->setPosition( $position );
            $services_sort->save();
        }

        wp_send_json_success();
    }

    /**
     * Add service.
     */
    public static function createService()
    {
        ! Lib\Config::proActive() &&
        get_option( 'bookly_updated_from_legacy_version' ) != 'lite' &&
        Lib\Entities\Service::query()->count() > 4 &&
        wp_send_json_error( array( 'message' => Limitation\Notice::forNewService() ) );

        $form = new Forms\Service();
        $form->bind( self::postParameters() );
        $form->getObject()->setDuration( Lib\Config::getTimeSlotLength() );
        $service = $form->save();

        Proxy\Shared::serviceCreated( $service );

        $sub_services_count = array_sum( array_map( function ( $sub_service ) {
            /** @var Lib\Entities\Service $sub_service */
            return (int) ( $sub_service->getType() == Lib\Entities\SubService::TYPE_SERVICE );
        }, $service->getSubServices() ) );

        wp_send_json_success( array(
            'id'       => $service->getId(),
            'type'     => $service->getType(),
            'title'    => $service->getTitle(),
            'category' => $service->getCategoryId(),
            'colors'   => Proxy\Shared::prepareServiceColors( array_fill( 0, 3, $service->getColor() ), $service->getId(), $service->getType() ),
            'duration' => in_array( $service->getType(), array(
                Lib\Entities\Service::TYPE_COLLABORATIVE,
                Lib\Entities\Service::TYPE_COMPOUND,
            ) ) ? sprintf( _n( '%d service', '%d services', $sub_services_count, 'bookly' ), $sub_services_count ) : Lib\Utils\DateTime::secondsToInterval( $service->getDuration() ),
        ) );
    }

    /**
     * 'Safely' remove services (report if there are future appointments)
     */
    public static function removeServices()
    {
        $service_ids = self::parameter( 'service_ids', array() );
        if ( self::parameter( 'force_delete', false ) ) {
            if ( is_array( $service_ids ) && ! empty ( $service_ids ) ) {
                foreach ( $service_ids as $service_id ) {
                    if ( $service = Lib\Entities\Service::find( $service_id ) ) {
                        Proxy\Shared::serviceDeleted( $service );
                        foreach ( Lib\Entities\Appointment::query( 'a' )->where( 'a.service_id', $service_id )->find() as $appointment ) {
                            Lib\Utils\Log::deleteEntity( $appointment, __METHOD__, 'Delete service: ' . $service->getTitle() );
                        }
                        $service->delete();
                    }
                }
            }
        } else {
            $appointment = Lib\Entities\Appointment::query( 'a' )
                ->select( 'a.service_id, a.start_date' )
                ->leftJoin( 'CustomerAppointment', 'ca', 'ca.appointment_id = a.id' )
                ->whereIn( 'a.service_id', $service_ids )
                ->whereGt( 'a.start_date', current_time( 'mysql' ) )
                ->whereIn( 'ca.status', Lib\Proxy\CustomStatuses::prepareBusyStatuses( array(
                    Lib\Entities\CustomerAppointment::STATUS_PENDING,
                    Lib\Entities\CustomerAppointment::STATUS_APPROVED,
                ) ) )
                ->sortBy( 'a.start_date' )
                ->order( 'DESC' )
                ->limit( '1' )
                ->fetchRow();

            if ( $appointment ) {
                $last_month = date_create( $appointment['start_date'] )->modify( 'last day of' )->format( 'Y-m-d' );
                $action     = 'show_modal';
                $filter_url = sprintf( '%s#service=%d&appointment-date=%s-%s',
                    Lib\Utils\Common::escAdminUrl( Appointments\Page::pageSlug() ),
                    $appointment['service_id'],
                    date_create( current_time( 'mysql' ) )->format( 'Y-m-d' ),
                    $last_month );
                wp_send_json_error( compact( 'action', 'filter_url' ) );
            } else if ( $task = Lib\Entities\Appointment::query( 'a' )
                ->select( 'a.service_id' )
                ->leftJoin( 'CustomerAppointment', 'ca', 'ca.appointment_id = a.id' )
                ->whereIn( 'a.service_id', $service_ids )
                ->where( 'a.start_date', null )
                ->whereIn( 'ca.status', Lib\Proxy\CustomStatuses::prepareBusyStatuses( array(
                    Lib\Entities\CustomerAppointment::STATUS_PENDING,
                    Lib\Entities\CustomerAppointment::STATUS_APPROVED,
                ) ) )
                ->limit( 1 )
                ->fetchRow()
            ) {
                $action     = 'show_modal';
                $filter_url = sprintf( '%s#service=%d&tasks',
                    Lib\Utils\Common::escAdminUrl( Appointments\Page::pageSlug() ),
                    $task['service_id'] );
                wp_send_json_error( compact( 'action', 'filter_url' ) );
            } else {
                $action = 'confirm';
                wp_send_json_error( compact( 'action' ) );
            }
        }

        wp_send_json_success();
    }

    /**
     * Update service categories
     */
    public static function updateServiceCategories()
    {
        $categories          = self::parameter( 'categories', array() );
        $existing_categories = array();
        foreach ( $categories as $category ) {
            if ( strpos( $category['id'], 'new' ) === false ) {
                $existing_categories[] = $category['id'];
            }
        }
        // Delete categories
        Lib\Entities\Category::query( 'c' )->delete()->whereNotIn( 'c.id', $existing_categories )->execute();
        foreach ( $categories as $position => $category_data ) {
            if ( strpos( $category_data['id'], 'new' ) === false ) {
                $category = Lib\Entities\Category::find( $category_data['id'] );
            } else {
                $category = new Lib\Entities\Category();
            }
            $category
                ->setPosition( $position )
                ->setName( $category_data['name'] )
                ->save();
        }

        wp_send_json_success( Lib\Entities\Category::query()->sortBy( 'position' )->fetchArray() );
    }

    /**
     * Duplicate service.
     */
    public static function duplicateService()
    {
        ! Lib\Config::proActive() &&
        get_option( 'bookly_updated_from_legacy_version' ) != 'lite' &&
        Lib\Entities\Service::query()->count() > 4 &&
        wp_send_json_error( array( 'message' => Limitation\Notice::forNewService() ) );
        $service_id = self::parameter( 'service_id' );
        $service    = Lib\Entities\Service::find( $service_id );
        if ( $service ) {
            // Create copy of service
            $new_service = new Lib\Entities\Service( $service->getFields() );
            $new_service
                ->setId( null )
                ->setTitle( sprintf( __( 'Copy of %s', 'bookly' ), $new_service->getTitle() ) )
                ->setVisibility( Lib\Entities\Service::VISIBILITY_PRIVATE )
                ->save();

            foreach ( Lib\Entities\StaffService::query()->where( 'service_id', $service->getId() )->fetchArray() as $staff_service ) {
                $new_staff_service = new Lib\Entities\StaffService( $staff_service );
                $new_staff_service->setId( null )->setServiceId( $new_service->getId() )->save();
            }

            foreach ( Lib\Entities\SubService::query()->where( 'service_id', $service->getId() )->fetchArray() as $sub_service ) {
                $new_sub_service = new Lib\Entities\SubService( $sub_service );
                $new_sub_service->setId( null )->setServiceId( $new_service->getId() )->save();
            }

            Proxy\Shared::duplicateService( $service->getId(), $new_service->getId() );

            wp_send_json_success( array(
                'id'    => $new_service->getId(),
                'title' => $new_service->getTitle(),
            ) );
        }

        wp_send_json_success();
    }
}