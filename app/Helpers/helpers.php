<?php

if (!function_exists('calculate_discount')) {
    /**
     * Calculate the discount amount.
     *
     * @param float $bookingCost
     * @param object $discount
     * @return float
     */
    function calculate_discount($bookingCost, $discount)
    {
        if ($discount->discount_type == 'percentage') {
            return ($bookingCost * $discount->amount) / 100;
        } elseif ($discount->discount_type == 'amount') {
            return $discount->amount;
        }
        return 0;
    }
}