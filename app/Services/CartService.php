<?php

class CartService
{
    public static function getCartData()
    {
        if (!defined('APP_INIT')) {
            http_response_code(403);
            exit("Access denied");
        }

        $pdo = $GLOBALS['pdo'];

        // CART TOTAL (totaal)
        $totaal = 0;
        $resultMinimal = null;

        if (!empty($_SESSION['cart'])) {
            $productIds = array_keys($_SESSION['cart']);

            $idsString = implode(',', array_map('intval', $productIds));

            $sql = "SELECT id, discounted_price FROM products WHERE id IN ($idsString)";
            $resultMinimal = $pdo->query($sql);

            while ($r = $resultMinimal->fetch(PDO::FETCH_ASSOC)) {
                $pid = $r['id'];
                $qty = $_SESSION['cart'][$pid]['quantity'] ?? 0;
                $totaal += $r['discounted_price'] * $qty;
            }
        }

        // For PayPal button
        $orderId = $_SESSION['current_order_id'] ?? null;

        // FULL CART DETAILS (used in the big cart list)
        $resultFull = null;
        if (!empty($_SESSION['cart'])) {
            $productIds = array_keys($_SESSION['cart']);
            $idsString = implode(',', array_map('intval', $productIds));

            $query = "SELECT id, name, description, original_price, discounted_price, image, base_quantity, order_unit 
                      FROM products 
                      WHERE id IN ($idsString)";
            $resultFull = $pdo->query($query);
        }

        // ADDRESS SECTION — dropdown lists
        $states = $pdo->query("SELECT StateID, StateName FROM MasState WHERE Active = 1 ORDER BY StateName")->fetchAll(PDO::FETCH_ASSOC);
        $districts = $pdo->query("SELECT DistrictID, DistrictName FROM MasDistrict WHERE Active = 1 ORDER BY DistrictName")->fetchAll(PDO::FETCH_ASSOC);
        $addressTypes = $pdo->query("SELECT AddressTypeID, AddressTypeName FROM AddressType ORDER BY AddressTypeName")->fetchAll(PDO::FETCH_ASSOC);

        return [
            'totaal' => $totaal,
            'orderId' => $orderId,
            'resultMinimal' => $resultMinimal,   // for computing totals
            'resultFull' => $resultFull,         // for big cart list
            'states' => $states,
            'districts' => $districts,
            'addressTypes' => $addressTypes
        ];
    }
}
