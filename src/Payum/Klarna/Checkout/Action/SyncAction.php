<?php
namespace Payum\Klarna\Checkout\Action;

use Payum\Core\Action\PaymentAwareAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Sync;
use Payum\Klarna\Checkout\Constants;
use Payum\Klarna\Checkout\Request\Api\FetchOrder;

class SyncAction extends PaymentAwareAction
{
    /**
     * {@inheritDoc}
     */
    public function execute($request)
    {
        /** @var $request \Payum\Core\Request\Sync */
        if (false == $this->supports($request)) {
            throw RequestNotSupportedException::createActionNotSupported($this, $request);
        }

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if ($model['location']) {
            $this->payment->execute($fetchOrder = new FetchOrder($model));

            $model->replace($fetchOrder->getOrder()->marshal());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Sync &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}