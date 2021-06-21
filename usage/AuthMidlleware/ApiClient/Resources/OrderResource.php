<?php


namespace Example\AuthMiddleware\ApiClient\Resources;


use fGalvao\BaseClientApi\Resource;

/**
 * Class SfOrder
 *
 * @property string $id
 * @property bool   $isDeleted
 * @property string $name
 * @property string $createdDate
 * @property string $createdById
 * @property string $lastModifiedDate
 * @property string $lastModifiedById
 * @property string $account
 * @property string $telephone
 * @property string $orderDate
 * @property string $orderStatus
 * @property float  $grandTotal
 * @property float  $totalPaid
 * @property float  $subtotal
 * @property float  $discount
 * @property float  $taxAmount
 * @property float  $shippingAmount
 * @property string $paymentMethod
 * @property string $edit_order_in_Magento
 * @property bool   $the_School_Musicals_Company_Product_Sent
 * @property bool   $disputed
 * @property string $order_Method
 * @property string $contact
 * @property double $syncStamp
 * @property string $statusHistory
 * @property string $customerEmail
 * @property string $organisationName
 * @property string $membership_Product
 * @property string $accountAddress
 *
 * @package SfApi\Resources
 */
class OrderResource extends Resource
{
    protected $ignore = [
        'attributes'
    ];
    
}