<?php

namespace App\Http\Controllers;

use App\Models\Bookings;
use App\Models\Discount;
use App\Models\Family;
use Illuminate\Http\Request;

class BookingsController extends Controller
{
    public function bookingSchedule(Request $request)
    {
        $userId = $request->user()->id;
        $scheduleId = $request->schedule_id;
        $bookingAmount = $request->amount;

        $totalDiscount = 0;

        $familyDiscount = $this->applyFamilyDiscount($userId, $scheduleId);
        if ($familyDiscount) {
            $totalDiscount += calculate_discount($bookingAmount, $familyDiscount);
        }

        $recurringDiscount = $this->applyRecurringDiscount($userId, $scheduleId);
        if ($recurringDiscount) {
            $totalDiscount += calculate_discount($bookingAmount, $recurringDiscount);
        }

        $finalAmount = $bookingAmount - $totalDiscount;

        $booking = Bookings::create([
            'user_id' => $userId,
            'schedule_id' => $scheduleId,
            'amount' => $finalAmount
        ]);

        return response()->json([
            'booking' => $booking,
            'original_amount' => $bookingAmount,
            'total_discount' => $totalDiscount,
            'final_amount' => $finalAmount
        ]);
    }

    private function applyFamilyDiscount($userId, $scheduleId)
    {

        $familyMembers = Family::where('user_id', $userId)->first();

        if ($familyMembers) {

            $familyBookings = Bookings::whereIn('user_id', $familyMembers->member_ids)
                ->where('schedule_id', $scheduleId)
                ->exists();

            if ($familyBookings) {
                return Discount::where('type', 'family')->first();
            }
        }

        return null;
    }

    private function applyRecurringDiscount($userId, $scheduleId)
    {

        $userBooking = Bookings::where('user_id', $userId)
            ->where('schedule_id', $scheduleId)
            ->exists();

        if ($userBooking) {
            return Discount::where('type', 'recurring')->first();
        }

        return null;
    }
}