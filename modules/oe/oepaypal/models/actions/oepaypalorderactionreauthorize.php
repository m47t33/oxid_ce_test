<?php
/**
 * This file is part of OXID eSales PayPal module.
 *
 * OXID eSales PayPal module is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OXID eSales PayPal module is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OXID eSales PayPal module.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2013
 * @version OXID eSales PayPal CE
 */

/**
 * PayPal order action reauthorize class
 */
class oePayPalOrderActionReauthorize extends oePayPalOrderAction
{
    /**
     * Amount to reauthorize
     *
     * @var double
     */
    protected $_dAmount = null;

    /**
     * PayPal Request object
     *
     * @var oePayPalRequest
     */
    protected $_oPayPalRequest = null;

    /**
     * PayPal Response object
     *
     * @var oePayPalResponseDoRefund
     */
    protected $_oPayPalResponse = null;

    /**
     * Processes PayPal response
     */
    public function process()
    {
        $oResponse = $this->getPayPalResponse();

        $oOrder = $this->getOrder();
        $this->_changeOrderStatus();
        $oOrder->save();

        $oPayment = oxNew( 'oePayPalOrderPayment' );
        $oPayment->setDate($this->getDate());
        $oPayment->setTransactionId( $oResponse->getAuthorizationId() );
        $oPayment->setCorrelationId( $oResponse->getCorrelationId() );
        $oPayment->setAction('re-authorization');
        $oPayment->setStatus( $oResponse->getPaymentStatus() );

        $oPayment = $oOrder->getPaymentList()->addPayment( $oPayment );

        if ( $this->getComment() ) {
            $oComment = oxNew('oePayPalOrderPaymentComment');
            $oComment->setComment( $this->getComment() );
            $oPayment->addComment( $oComment );
        }
    }

    /**
     * Returns PayPal response; initiates if not set
     *
     * @return oePayPalResponseDoRefund
     */
    public function getPayPalResponse()
    {
        if ( is_null( $this->_oPayPalResponse ) ) {
            $oService = $this->getPayPalService();
            $oRequest = $this->getPayPalRequest();
            $this->_oPayPalResponse = $oService->doReAuthorization( $oRequest );
        }
        return $this->_oPayPalResponse;
    }

    /**
     * Sets PayPal response
     *
     * @param oePayPalResponseDoRefund $oPayPalResponse
     */
    public function setPayPalResponse( $oPayPalResponse )
    {
        $this->_oPayPalResponse = $oPayPalResponse;
    }

    /**
     * Returns PayPal request; initiates if not set
     *
     * @return oePayPalPayPalRequest
     */
    public function getPayPalRequest()
    {
        if ( is_null( $this->_oPayPalRequest ) ) {
            $oRequestBuilder = $this->getPayPalRequestBuilder();

            $oRequestBuilder->setAuthorizationId( $this->getAuthorizationId() );
            $oRequestBuilder->setAmount( $this->getAmount(), $this->getOrder()->getCurrency() );

            $this->_oPayPalRequest = $oRequestBuilder->getRequest();
        }

        return $this->_oPayPalRequest;
    }

    /**
     * Sets PayPal request
     *
     * @param oePayPalPayPalRequest $oPayPalRequest
     */
    public function setPayPalRequest( $oPayPalRequest )
    {
        $this->_oPayPalRequest = $oPayPalRequest;
    }

    /**
     * Returns amount to reauthorize
     *
     * @return double
     */
    public function getAmount()
    {
        if ( is_null( $this->_dAmount ) ) {
            $this->_dAmount = $this->getOrder()->getRemainingOrderSum();
        }
        return $this->_dAmount;
    }

    /**
     * Sets amount to reauthorize
     *
     * @param double $dAmount
     */
    public function setAmount( $dAmount )
    {
        $this->_dAmount = $dAmount;
    }
}