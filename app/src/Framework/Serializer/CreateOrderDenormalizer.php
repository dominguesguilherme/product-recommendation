<?php

namespace ProductRecommendation\Framework\Serializer;

use ProductRecommendation\Core\Application\CreateOrder;
use ProductRecommendation\Core\Application\OrderItemDTO;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class CreateOrderDenormalizer implements DenormalizerInterface
{
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        if (!is_array($data)) {
            return null;
        }

        $items = [];
        foreach ($data['items'] as $itemData) {
            $items[] = new OrderItemDTO(
                $itemData['id'],
                $itemData['productId'],
                $itemData['unitPrice'],
                $itemData['quantity']
            );
        }

        $createOrder = new CreateOrder(
            $data['id'],
            new \DateTimeImmutable($data['createdAt']),
            $items
        );

        return $createOrder;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === CreateOrder::class;
    }
}
