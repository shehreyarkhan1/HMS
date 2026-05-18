<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $patient_id
 * @property int|null $doctor_id
 * @property \Illuminate\Support\Carbon $appointment_date
 * @property string|null $appointment_time
 * @property int $duration_minutes
 * @property int|null $token_number
 * @property string $type
 * @property string $status
 * @property string|null $reason
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $consulted_at
 * @property \Illuminate\Support\Carbon|null $follow_up_date
 * @property string|null $cancelled_by
 * @property string|null $cancellation_reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BillItem> $billItems
 * @property-read int|null $bill_items_count
 * @property-read \App\Models\Doctor|null $doctor
 * @property-read string $formatted_date
 * @property-read string $formatted_time
 * @property-read bool $is_overdue
 * @property-read bool $is_today
 * @property-read bool $is_upcoming
 * @property-read \App\Models\Patient|null $patient
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment byStatus($status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment forDoctor($doctorId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment forPatient($patientId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment scheduled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment today()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment upcoming()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereAppointmentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereAppointmentTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereCancellationReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereCancelledBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereConsultedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereDurationMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereFollowUpDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereTokenNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment withoutTrashed()
 */
	class Appointment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $bed_number
 * @property int $ward_id
 * @property string $type
 * @property string $status
 * @property int|null $patient_id
 * @property \Illuminate\Support\Carbon|null $admitted_at
 * @property \Illuminate\Support\Carbon|null $discharged_at
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Patient|null $patient
 * @property-read \App\Models\Ward $ward
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bed available()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bed occupied()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bed query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bed whereAdmittedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bed whereBedNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bed whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bed whereDischargedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bed whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bed whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bed wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bed whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bed whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bed whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bed whereWardId($value)
 */
	class Bed extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $bill_number
 * @property int $patient_id
 * @property int|null $created_by
 * @property int|null $discount_by
 * @property \Illuminate\Support\Carbon $bill_date
 * @property string $bill_type
 * @property string $status
 * @property numeric $subtotal
 * @property numeric $discount_amount
 * @property string|null $discount_reason
 * @property numeric $tax_amount
 * @property numeric $net_amount
 * @property numeric $paid_amount
 * @property numeric $due_amount
 * @property string $payment_status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $finalized_at
 * @property \Illuminate\Support\Carbon|null $cancelled_at
 * @property string|null $cancellation_reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $createdBy
 * @property-read \App\Models\User|null $discountBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BillItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Patient|null $patient
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BillPayment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill draft()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill finalized()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill unpaid()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill whereBillDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill whereBillNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill whereBillType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill whereCancellationReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill whereCancelledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill whereDiscountBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill whereDiscountReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill whereDueAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill whereFinalizedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill whereNetAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill wherePaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bill withoutTrashed()
 */
	class Bill extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $bill_id
 * @property string $service_type
 * @property string $description
 * @property string|null $reference_type
 * @property int|null $reference_id
 * @property numeric $quantity
 * @property numeric $unit_price
 * @property numeric $discount
 * @property numeric $total_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Bill|null $bill
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $reference
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillItem whereBillId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillItem whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillItem whereReferenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillItem whereReferenceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillItem whereServiceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillItem whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillItem whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillItem whereUpdatedAt($value)
 */
	class BillItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $payment_number
 * @property int $bill_id
 * @property int|null $received_by
 * @property numeric $amount
 * @property string $payment_method
 * @property \Illuminate\Support\Carbon $payment_date
 * @property string|null $reference_number
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Bill|null $bill
 * @property-read \App\Models\User|null $receivedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillPayment whereBillId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillPayment whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillPayment wherePaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillPayment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillPayment wherePaymentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillPayment whereReceivedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillPayment whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillPayment whereUpdatedAt($value)
 */
	class BillPayment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $category
 * @property string|null $blood_component
 * @property numeric $default_price
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillServiceCharge active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillServiceCharge byCategory($category)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillServiceCharge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillServiceCharge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillServiceCharge query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillServiceCharge whereBloodComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillServiceCharge whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillServiceCharge whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillServiceCharge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillServiceCharge whereDefaultPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillServiceCharge whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillServiceCharge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillServiceCharge whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillServiceCharge whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BillServiceCharge whereUpdatedAt($value)
 */
	class BillServiceCharge extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $crossmatch_id
 * @property int $blood_request_id
 * @property int $blood_donation_id
 * @property string $result
 * @property string $method
 * @property \Illuminate\Support\Carbon|null $performed_at
 * @property int|null $performed_by
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BloodDonation|null $donation
 * @property-read \App\Models\Employee|null $performedBy
 * @property-read \App\Models\BloodRequest|null $request
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodCrossmatch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodCrossmatch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodCrossmatch query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodCrossmatch whereBloodDonationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodCrossmatch whereBloodRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodCrossmatch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodCrossmatch whereCrossmatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodCrossmatch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodCrossmatch whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodCrossmatch whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodCrossmatch wherePerformedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodCrossmatch wherePerformedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodCrossmatch whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodCrossmatch whereUpdatedAt($value)
 */
	class BloodCrossmatch extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $donation_id
 * @property int $donor_id
 * @property \Illuminate\Support\Carbon $donation_date
 * @property string|null $donation_time
 * @property string $blood_group
 * @property numeric $volume_ml
 * @property string|null $bag_number
 * @property string $component
 * @property string $screening_status
 * @property bool $hiv_tested
 * @property bool $hbsag_tested
 * @property bool $hcv_tested
 * @property bool $vdrl_tested
 * @property bool $malaria_tested
 * @property string|null $screening_notes
 * @property string $status
 * @property \Illuminate\Support\Carbon $expiry_date
 * @property int|null $collected_by
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Employee|null $collectedBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BloodCrossmatch> $crossmatches
 * @property-read int|null $crossmatches_count
 * @property-read \App\Models\BloodDonor|null $donor
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BloodIssue> $issue
 * @property-read int|null $issue_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation available()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation expiringSoon(int $days = 3)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation whereBagNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation whereBloodGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation whereCollectedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation whereComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation whereDonationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation whereDonationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation whereDonationTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation whereDonorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation whereHbsagTested($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation whereHcvTested($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation whereHivTested($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation whereMalariaTested($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation whereScreeningNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation whereScreeningStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation whereVdrlTested($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation whereVolumeMl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonation withoutTrashed()
 */
	class BloodDonation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $donor_id
 * @property string $donor_type
 * @property string $name
 * @property string|null $father_name
 * @property \Illuminate\Support\Carbon $date_of_birth
 * @property string $gender
 * @property string $blood_group
 * @property numeric|null $weight_kg
 * @property string|null $cnic
 * @property string $phone
 * @property string|null $email
 * @property string|null $address
 * @property string|null $city
 * @property bool $is_eligible
 * @property string|null $ineligibility_reason
 * @property \Illuminate\Support\Carbon|null $eligible_from
 * @property \Illuminate\Support\Carbon|null $last_donation_date
 * @property int $total_donations
 * @property \Illuminate\Support\Carbon|null $next_eligible_date
 * @property int|null $patient_id
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BloodDonation> $donations
 * @property-read int|null $donations_count
 * @property-read int $age
 * @property-read \App\Models\Patient|null $patient
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor byBloodGroup(string $group)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor eligible()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor whereBloodGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor whereCnic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor whereDonorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor whereDonorType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor whereEligibleFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor whereFatherName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor whereIneligibilityReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor whereIsEligible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor whereLastDonationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor whereNextEligibleDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor whereTotalDonations($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor whereWeightKg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodDonor withoutTrashed()
 */
	class BloodDonor extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $blood_group
 * @property string $component
 * @property int $units_available
 * @property int $units_reserved
 * @property int $minimum_threshold
 * @property \Illuminate\Support\Carbon|null $last_updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodInventory criticalStock()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodInventory lowStock()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodInventory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodInventory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodInventory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodInventory whereBloodGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodInventory whereComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodInventory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodInventory whereLastUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodInventory whereMinimumThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodInventory whereUnitsAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodInventory whereUnitsReserved($value)
 */
	class BloodInventory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $issue_id
 * @property int $blood_request_id
 * @property int $blood_donation_id
 * @property int $patient_id
 * @property string $blood_group
 * @property string|null $bag_number
 * @property numeric|null $volume_ml
 * @property string $component
 * @property \Illuminate\Support\Carbon $issued_at
 * @property \Illuminate\Support\Carbon|null $transfusion_started_at
 * @property \Illuminate\Support\Carbon|null $transfusion_completed_at
 * @property bool $reaction_observed
 * @property string $reaction_type
 * @property string|null $reaction_notes
 * @property int|null $issued_by
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BloodDonation|null $donation
 * @property-read \App\Models\Employee|null $issuedBy
 * @property-read \App\Models\Patient|null $patient
 * @property-read \App\Models\BloodRequest|null $request
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodIssue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodIssue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodIssue query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodIssue whereBagNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodIssue whereBloodDonationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodIssue whereBloodGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodIssue whereBloodRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodIssue whereComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodIssue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodIssue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodIssue whereIssueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodIssue whereIssuedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodIssue whereIssuedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodIssue whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodIssue wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodIssue whereReactionNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodIssue whereReactionObserved($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodIssue whereReactionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodIssue whereTransfusionCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodIssue whereTransfusionStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodIssue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodIssue whereVolumeMl($value)
 */
	class BloodIssue extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $request_id
 * @property int $patient_id
 * @property int|null $doctor_id
 * @property string $blood_group
 * @property string $component
 * @property int $units_required
 * @property int $units_approved
 * @property string $urgency
 * @property string $indication
 * @property string|null $ward
 * @property string|null $bed_number
 * @property numeric|null $patient_hemoglobin
 * @property string $status
 * @property string|null $rejection_reason
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property \Illuminate\Support\Carbon|null $fulfilled_at
 * @property int|null $processed_by
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BillItem> $billItems
 * @property-read int|null $bill_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BloodCrossmatch> $crossmatches
 * @property-read int|null $crossmatches_count
 * @property-read \App\Models\Doctor|null $doctor
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BloodIssue> $issues
 * @property-read int|null $issues_count
 * @property-read \App\Models\Patient|null $patient
 * @property-read \App\Models\Employee|null $processedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest urgent()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest whereBedNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest whereBloodGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest whereComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest whereFulfilledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest whereIndication($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest wherePatientHemoglobin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest whereProcessedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest whereRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest whereUnitsApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest whereUnitsRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest whereUrgency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest whereWard($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodRequest withoutTrashed()
 */
	class BloodRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $dispense_number
 * @property int|null $prescription_id
 * @property int $patient_id
 * @property \Illuminate\Support\Carbon $dispensed_at
 * @property numeric $total_amount
 * @property string $payment_status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DispensingItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Patient|null $patient
 * @property-read \App\Models\Prescription|null $prescription
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dispensing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dispensing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dispensing query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dispensing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dispensing whereDispenseNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dispensing whereDispensedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dispensing whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dispensing whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dispensing wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dispensing wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dispensing wherePrescriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dispensing whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dispensing whereUpdatedAt($value)
 */
	class Dispensing extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $dispensing_id
 * @property int|null $prescription_item_id
 * @property int $medicine_id
 * @property int $medicine_batch_id
 * @property int $quantity
 * @property numeric $unit_price
 * @property numeric $total_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\MedicineBatch $batch
 * @property-read \App\Models\Dispensing $dispensing
 * @property-read \App\Models\Medicine|null $medicine
 * @property-read \App\Models\PrescriptionItem|null $prescriptionItem
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DispensingItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DispensingItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DispensingItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DispensingItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DispensingItem whereDispensingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DispensingItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DispensingItem whereMedicineBatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DispensingItem whereMedicineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DispensingItem wherePrescriptionItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DispensingItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DispensingItem whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DispensingItem whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DispensingItem whereUpdatedAt($value)
 */
	class DispensingItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $employee_id
 * @property string $doctor_id
 * @property string $specialization
 * @property string $qualification
 * @property string|null $pmdc_number
 * @property string|null $sub_department
 * @property string $doctor_type
 * @property numeric $consultation_fee
 * @property int $avg_consultation_mins
 * @property string $availability
 * @property array<array-key, mixed>|null $available_days
 * @property string|null $bio
 * @property string|null $clinical_notes
 * @property bool $is_active
 * @property int $accepts_new_patients
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Appointment> $appointments
 * @property-read int|null $appointments_count
 * @property-read \App\Models\Employee|null $employee
 * @property-read mixed $display_name
 * @property-read mixed $email
 * @property-read mixed $full_display
 * @property-read mixed $initials
 * @property-read mixed $name
 * @property-read mixed $photo_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Patient> $patients
 * @property-read int|null $patients_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor available()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor byDepartment($dept)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereAcceptsNewPatients($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereAvailability($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereAvailableDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereAvgConsultationMins($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereClinicalNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereConsultationFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereDoctorType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor wherePmdcNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereQualification($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereSpecialization($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereSubDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor withoutTrashed()
 */
	class Doctor extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $employee_id
 * @property string|null $badge_number
 * @property string $first_name
 * @property string $last_name
 * @property string|null $father_name
 * @property string|null $mother_name
 * @property \Illuminate\Support\Carbon|null $date_of_birth
 * @property string $gender
 * @property string $marital_status
 * @property string|null $religion
 * @property string $nationality
 * @property string|null $cnic
 * @property \Illuminate\Support\Carbon|null $cnic_expiry
 * @property string|null $blood_group
 * @property string|null $photo
 * @property string $personal_phone
 * @property string|null $office_phone
 * @property string|null $personal_email
 * @property string|null $office_email
 * @property string|null $emergency_contact_name
 * @property string|null $emergency_contact_phone
 * @property string|null $emergency_contact_relation
 * @property string|null $present_address
 * @property string|null $permanent_address
 * @property string|null $city
 * @property string|null $province
 * @property string|null $postal_code
 * @property string $department
 * @property string $designation
 * @property string|null $job_grade
 * @property string $employment_type
 * @property string $employment_status
 * @property \Illuminate\Support\Carbon $joining_date
 * @property \Illuminate\Support\Carbon|null $confirmation_date
 * @property \Illuminate\Support\Carbon|null $contract_end_date
 * @property \Illuminate\Support\Carbon|null $resignation_date
 * @property \Illuminate\Support\Carbon|null $termination_date
 * @property string|null $termination_reason
 * @property string $shift
 * @property string|null $shift_start
 * @property string|null $shift_end
 * @property int $weekly_hours
 * @property array<array-key, mixed>|null $working_days
 * @property string|null $highest_qualification
 * @property string|null $specialization
 * @property string|null $institution
 * @property string|null $graduation_year
 * @property int $total_experience_years
 * @property string|null $previous_employer
 * @property string|null $previous_designation
 * @property string|null $bank_name
 * @property string|null $bank_account_number
 * @property string|null $bank_branch
 * @property string|null $iban
 * @property string $salary_type
 * @property numeric $basic_salary
 * @property string|null $ntn_number
 * @property string|null $eobi_number
 * @property string|null $socso_number
 * @property bool $is_tax_filer
 * @property bool $has_system_access
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Doctor|null $doctor
 * @property-read int|null $age
 * @property-read string $full_name
 * @property-read string $initials
 * @property-read bool $is_contract_expiring_soon
 * @property-read bool $is_on_probation
 * @property-read string|null $photo_url
 * @property-read int $service_months
 * @property-read int $service_years
 * @property-read string $status_color
 * @property-read Employee|null $manager
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Employee> $subordinates
 * @property-read int|null $subordinates_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee byDepartment(string $dept)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee byType(string $type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee contractExpiringSoon()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee search(string $term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBadgeNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBankAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBankBranch($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBasicSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBloodGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCnic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCnicExpiry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereConfirmationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereContractEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDesignation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmergencyContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmergencyContactPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmergencyContactRelation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmploymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmploymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEobiNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereFatherName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereGraduationYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereHasSystemAccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereHighestQualification($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereIban($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereInstitution($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereIsTaxFiler($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereJobGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereJoiningDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMaritalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMotherName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereNationality($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereNtnNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereOfficeEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereOfficePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePermanentAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePersonalEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePersonalPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePresentAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePreviousDesignation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePreviousEmployer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereReligion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereResignationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereSalaryType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereShift($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereShiftEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereShiftStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereSocsoNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereSpecialization($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereTerminationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereTerminationReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereTotalExperienceYears($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereWeeklyHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereWorkingDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee withoutTrashed()
 */
	class Employee extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $order_number
 * @property int $patient_id
 * @property int $doctor_id
 * @property int|null $appointment_id
 * @property \Illuminate\Support\Carbon $order_date
 * @property string $priority
 * @property string $status
 * @property numeric $total_amount
 * @property numeric $discount
 * @property numeric $paid_amount
 * @property string $payment_status
 * @property bool $report_delivered
 * @property \Illuminate\Support\Carbon|null $report_delivered_at
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Appointment|null $appointment
 * @property-read \App\Models\Doctor|null $doctor
 * @property-read float $balance
 * @property-read bool $is_completed
 * @property-read bool $is_fully_paid
 * @property-read float $net_amount
 * @property-read string $priority_color
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LabOrderItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Patient|null $patient
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LabSample> $samples
 * @property-read int|null $samples_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder byPriority($priority)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder today()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder whereAppointmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder whereOrderDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder wherePaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder whereReportDelivered($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder whereReportDeliveredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrder withoutTrashed()
 */
	class LabOrder extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $lab_order_id
 * @property int $lab_test_id
 * @property int|null $lab_sample_id
 * @property numeric $price
 * @property numeric $discount
 * @property numeric $final_price
 * @property string $status
 * @property string|null $technician_name
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read bool $has_result
 * @property-read bool $is_completed
 * @property-read \App\Models\LabOrder|null $labOrder
 * @property-read \App\Models\LabTest $labTest
 * @property-read \App\Models\LabResult|null $result
 * @property-read \App\Models\LabSample|null $sample
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrderItem whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrderItem whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrderItem whereFinalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrderItem whereLabOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrderItem whereLabSampleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrderItem whereLabTestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrderItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrderItem whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrderItem whereTechnicianName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabOrderItem whereUpdatedAt($value)
 */
	class LabOrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $lab_order_item_id
 * @property string|null $result_value
 * @property string|null $unit
 * @property string|null $normal_range
 * @property string|null $flag
 * @property bool $is_abnormal
 * @property string|null $previous_value
 * @property \Illuminate\Support\Carbon|null $previous_date
 * @property string|null $remarks
 * @property \Illuminate\Support\Carbon|null $reported_at
 * @property int|null $verified_by
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property bool $is_verified
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $flag_bg
 * @property-read string $flag_color
 * @property-read bool $is_critical
 * @property-read \App\Models\LabOrderItem $orderItem
 * @property-read \App\Models\User|null $verifiedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabResult query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabResult whereFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabResult whereIsAbnormal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabResult whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabResult whereLabOrderItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabResult whereNormalRange($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabResult wherePreviousDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabResult wherePreviousValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabResult whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabResult whereReportedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabResult whereResultValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabResult whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabResult whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabResult whereVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabResult whereVerifiedBy($value)
 */
	class LabResult extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $sample_number
 * @property int $lab_order_id
 * @property int $sample_type_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $collected_at
 * @property \Illuminate\Support\Carbon|null $received_at
 * @property \Illuminate\Support\Carbon|null $processed_at
 * @property string|null $collected_by
 * @property string|null $rejection_reason
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read bool $is_rejected
 * @property-read string $status_color
 * @property-read \App\Models\LabOrder|null $labOrder
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LabOrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read \App\Models\LabSampleType $sampleType
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSample newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSample newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSample query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSample whereCollectedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSample whereCollectedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSample whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSample whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSample whereLabOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSample whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSample whereProcessedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSample whereReceivedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSample whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSample whereSampleNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSample whereSampleTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSample whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSample whereUpdatedAt($value)
 */
	class LabSample extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $container_type
 * @property string|null $color_code
 * @property int|null $volume_required
 * @property bool $requires_fasting
 * @property string|null $collection_instructions
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LabSample> $samples
 * @property-read int|null $samples_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LabTest> $tests
 * @property-read int|null $tests_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSampleType active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSampleType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSampleType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSampleType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSampleType whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSampleType whereCollectionInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSampleType whereColorCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSampleType whereContainerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSampleType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSampleType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSampleType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSampleType whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSampleType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSampleType whereRequiresFasting($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSampleType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabSampleType whereVolumeRequired($value)
 */
	class LabSampleType extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $test_code
 * @property int $category_id
 * @property int|null $sample_type_id
 * @property numeric $price
 * @property string|null $unit
 * @property string|null $normal_range
 * @property string|null $normal_range_male
 * @property string|null $normal_range_female
 * @property string|null $normal_range_child
 * @property string|null $normal_range_elderly
 * @property int $turnaround_hours
 * @property string|null $method
 * @property bool $requires_fasting
 * @property bool $is_active
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\LabTestCategory $category
 * @property-read string $turnaround_label
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LabOrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read \App\Models\LabSampleType|null $sampleType
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTest active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTest byCategory($categoryId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTest search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTest whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTest whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTest whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTest whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTest whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTest whereNormalRange($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTest whereNormalRangeChild($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTest whereNormalRangeElderly($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTest whereNormalRangeFemale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTest whereNormalRangeMale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTest wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTest whereRequiresFasting($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTest whereSampleTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTest whereTestCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTest whereTurnaroundHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTest whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTest whereUpdatedAt($value)
 */
	class LabTest extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LabTest> $tests
 * @property-read int|null $tests_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTestCategory active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTestCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTestCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTestCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTestCategory whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTestCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTestCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTestCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTestCategory whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTestCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LabTestCategory whereUpdatedAt($value)
 */
	class LabTestCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $medicine_code
 * @property string $name
 * @property string|null $generic_name
 * @property string|null $brand
 * @property string $category
 * @property string $unit
 * @property numeric $purchase_price
 * @property numeric $sale_price
 * @property int $reorder_level
 * @property int $total_stock
 * @property bool $requires_prescription
 * @property string|null $storage_condition
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicineBatch> $activeBatches
 * @property-read int|null $active_batches_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicineBatch> $batches
 * @property-read int|null $batches_count
 * @property-read bool $is_low_stock
 * @property-read bool $is_out_of_stock
 * @property-read string $stock_status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PrescriptionItem> $prescriptionItems
 * @property-read int|null $prescription_items_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine lowStock()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine outOfStock()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine whereGenericName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine whereMedicineCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine wherePurchasePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine whereReorderLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine whereRequiresPrescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine whereSalePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine whereStorageCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine whereTotalStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medicine withoutTrashed()
 */
	class Medicine extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $medicine_id
 * @property string $batch_number
 * @property \Illuminate\Support\Carbon $expiry_date
 * @property \Illuminate\Support\Carbon|null $manufacture_date
 * @property int $quantity_received
 * @property int $quantity_in_stock
 * @property numeric $purchase_price
 * @property string|null $supplier_name
 * @property string|null $supplier_invoice
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read int $days_to_expiry
 * @property-read bool $is_expired
 * @property-read bool $is_expiring_soon
 * @property-read \App\Models\Medicine|null $medicine
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicineBatch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicineBatch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicineBatch query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicineBatch whereBatchNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicineBatch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicineBatch whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicineBatch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicineBatch whereManufactureDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicineBatch whereMedicineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicineBatch wherePurchasePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicineBatch whereQuantityInStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicineBatch whereQuantityReceived($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicineBatch whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicineBatch whereSupplierInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicineBatch whereSupplierName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicineBatch whereUpdatedAt($value)
 */
	class MedicineBatch extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $room_code
 * @property string $name
 * @property string $room_type
 * @property string $status
 * @property bool $has_anesthesia_machine
 * @property bool $has_ventilator
 * @property bool $has_laparoscopy
 * @property bool $has_c_arm
 * @property bool $is_laminar_flow
 * @property string|null $equipment_notes
 * @property string|null $floor
 * @property string|null $block
 * @property string|null $notes
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OtSchedule> $activeSchedules
 * @property-read int|null $active_schedules_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OtSchedule> $schedules
 * @property-read int|null $schedules_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtRoom active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtRoom available()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtRoom newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtRoom newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtRoom onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtRoom query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtRoom whereBlock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtRoom whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtRoom whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtRoom whereEquipmentNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtRoom whereFloor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtRoom whereHasAnesthesiaMachine($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtRoom whereHasCArm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtRoom whereHasLaparoscopy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtRoom whereHasVentilator($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtRoom whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtRoom whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtRoom whereIsLaminarFlow($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtRoom whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtRoom whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtRoom whereRoomCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtRoom whereRoomType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtRoom whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtRoom whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtRoom withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtRoom withoutTrashed()
 */
	class OtRoom extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $surgery_id
 * @property int $patient_id
 * @property int|null $ot_room_id
 * @property int $surgeon_id
 * @property int|null $anesthesiologist_id
 * @property \Illuminate\Support\Carbon $scheduled_date
 * @property string $scheduled_time
 * @property int $estimated_duration_mins
 * @property \Illuminate\Support\Carbon|null $actual_start_time
 * @property \Illuminate\Support\Carbon|null $actual_end_time
 * @property string $surgery_type
 * @property string $priority
 * @property string|null $anesthesia_type
 * @property string $status
 * @property string $diagnosis
 * @property string $procedure_name
 * @property string|null $procedure_details
 * @property string|null $pre_op_instructions
 * @property string|null $post_op_notes
 * @property string|null $complications
 * @property string|null $post_op_destination
 * @property bool $consent_obtained
 * @property \Illuminate\Support\Carbon|null $consent_at
 * @property string|null $consent_by
 * @property bool $pre_op_assessment_done
 * @property string|null $pre_op_assessment_notes
 * @property string|null $postpone_reason
 * @property string|null $cancellation_reason
 * @property \Illuminate\Support\Carbon|null $rescheduled_date
 * @property int|null $booked_by
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Doctor|null $anesthesiologist
 * @property-read \App\Models\User|null $bookedBy
 * @property-read int|null $actual_duration_mins
 * @property-read string $scheduled_end_time
 * @property-read \App\Models\OtRoom|null $otRoom
 * @property-read \App\Models\Patient|null $patient
 * @property-read \App\Models\Doctor|null $surgeon
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OtTeam> $teamMembers
 * @property-read int|null $team_members_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule byStatus(string $status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule today()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule upcoming()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereActualEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereActualStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereAnesthesiaType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereAnesthesiologistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereBookedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereCancellationReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereComplications($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereConsentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereConsentBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereConsentObtained($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereDiagnosis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereEstimatedDurationMins($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereOtRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule wherePostOpDestination($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule wherePostOpNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule wherePostponeReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule wherePreOpAssessmentDone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule wherePreOpAssessmentNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule wherePreOpInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereProcedureDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereProcedureName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereRescheduledDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereScheduledDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereScheduledTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereSurgeonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereSurgeryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereSurgeryType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtSchedule withoutTrashed()
 */
	class OtSchedule extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $ot_schedule_id
 * @property string $role
 * @property int|null $doctor_id
 * @property int|null $employee_id
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Doctor|null $doctor
 * @property-read \App\Models\Employee|null $employee
 * @property-read string $member_name
 * @property-read \App\Models\OtSchedule|null $schedule
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtTeam newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtTeam newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtTeam query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtTeam whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtTeam whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtTeam whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtTeam whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtTeam whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtTeam whereOtScheduleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtTeam whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OtTeam whereUpdatedAt($value)
 */
	class OtTeam extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $mrn
 * @property string $name
 * @property string|null $father_name
 * @property \Illuminate\Support\Carbon $date_of_birth
 * @property string $gender
 * @property string|null $blood_group
 * @property string $phone
 * @property string|null $emergency_contact
 * @property string|null $emergency_relation
 * @property string|null $cnic
 * @property string|null $address
 * @property string|null $city
 * @property string $patient_type
 * @property string $status
 * @property int|null $doctor_id
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Appointment> $appointments
 * @property-read int|null $appointments_count
 * @property-read \App\Models\Bed|null $bed
 * @property-read \App\Models\Doctor|null $doctor
 * @property-read mixed $age
 * @property-read mixed $initials
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient byType($type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereBloodGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereCnic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereEmergencyContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereEmergencyRelation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereFatherName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereMrn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient wherePatientType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient withoutTrashed()
 */
	class Patient extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $prescription_number
 * @property int $patient_id
 * @property int|null $doctor_id
 * @property int|null $appointment_id
 * @property string $status
 * @property \Illuminate\Support\Carbon $prescribed_date
 * @property \Illuminate\Support\Carbon|null $valid_until
 * @property string|null $diagnosis
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Appointment|null $appointment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Dispensing> $dispensings
 * @property-read int|null $dispensings_count
 * @property-read \App\Models\Doctor|null $doctor
 * @property-read mixed $is_expired
 * @property-read mixed $status_color
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PrescriptionItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Patient|null $patient
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription search($term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription whereAppointmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription whereDiagnosis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription wherePrescribedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription wherePrescriptionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription whereValidUntil($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription withoutTrashed()
 */
	class Prescription extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $prescription_id
 * @property int $medicine_id
 * @property string $dosage
 * @property string $frequency
 * @property int $duration_days
 * @property int $quantity
 * @property int $dispensed_qty
 * @property string|null $instructions
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $is_fully_dispensed
 * @property-read mixed $remaining_qty
 * @property-read \App\Models\Medicine|null $medicine
 * @property-read \App\Models\Prescription|null $prescription
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem whereDispensedQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem whereDosage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem whereDurationDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem whereFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem whereInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem whereMedicineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem wherePrescriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem whereUpdatedAt($value)
 */
	class PrescriptionItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $region
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RadiologyExam> $exams
 * @property-read int|null $exams_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyBodyPart active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyBodyPart byRegion($region)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyBodyPart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyBodyPart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyBodyPart query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyBodyPart whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyBodyPart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyBodyPart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyBodyPart whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyBodyPart whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyBodyPart whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyBodyPart whereUpdatedAt($value)
 */
	class RadiologyBodyPart extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $radiology_order_id
 * @property string $consent_type
 * @property bool $is_signed
 * @property string|null $signed_by
 * @property string|null $relationship
 * @property \Illuminate\Support\Carbon|null $signed_at
 * @property string|null $witness
 * @property string|null $signature_path
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string|null $signature_url
 * @property-read \App\Models\RadiologyOrder|null $order
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyConsent byType($type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyConsent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyConsent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyConsent query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyConsent signed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyConsent unsigned()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyConsent whereConsentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyConsent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyConsent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyConsent whereIsSigned($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyConsent whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyConsent whereRadiologyOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyConsent whereRelationship($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyConsent whereSignaturePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyConsent whereSignedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyConsent whereSignedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyConsent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyConsent whereWitness($value)
 */
	class RadiologyConsent extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $exam_code
 * @property int $modality_id
 * @property int|null $body_part_id
 * @property numeric $price
 * @property bool $requires_contrast
 * @property string|null $contrast_type
 * @property bool $requires_preparation
 * @property string|null $preparation_instructions
 * @property int $turnaround_hours
 * @property int $duration_minutes
 * @property string|null $clinical_indications
 * @property string|null $contraindications
 * @property bool $requires_consent
 * @property bool $is_active
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\RadiologyBodyPart|null $bodyPart
 * @property-read string $formatted_price
 * @property-read \App\Models\RadiologyModality $modality
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RadiologyOrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyExam active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyExam byBodyPart($bodyPartId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyExam byModality($modalityId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyExam newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyExam newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyExam query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyExam requiresContrast()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyExam whereBodyPartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyExam whereClinicalIndications($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyExam whereContraindications($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyExam whereContrastType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyExam whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyExam whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyExam whereDurationMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyExam whereExamCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyExam whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyExam whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyExam whereModalityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyExam whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyExam wherePreparationInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyExam wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyExam whereRequiresConsent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyExam whereRequiresContrast($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyExam whereRequiresPreparation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyExam whereTurnaroundHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyExam whereUpdatedAt($value)
 */
	class RadiologyExam extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $radiology_order_item_id
 * @property string $file_path
 * @property string $file_name
 * @property string $file_type
 * @property string|null $mime_type
 * @property int|null $file_size
 * @property string|null $dicom_series_uid
 * @property string|null $dicom_instance_uid
 * @property string|null $view_position
 * @property array<array-key, mixed>|null $dicom_metadata
 * @property bool $is_primary
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $formatted_size
 * @property-read string $url
 * @property-read \App\Models\RadiologyOrderItem $orderItem
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyImage dicom()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyImage primary()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyImage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyImage whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyImage whereDicomInstanceUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyImage whereDicomMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyImage whereDicomSeriesUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyImage whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyImage whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyImage whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyImage whereFileType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyImage whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyImage whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyImage whereRadiologyOrderItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyImage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyImage whereViewPosition($value)
 */
	class RadiologyImage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $description
 * @property bool $requires_contrast
 * @property bool $requires_preparation
 * @property string|null $preparation_instructions
 * @property int $average_duration_minutes
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RadiologyExam> $activeExams
 * @property-read int|null $active_exams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RadiologyExam> $exams
 * @property-read int|null $exams_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyModality active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyModality newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyModality newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyModality query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyModality whereAverageDurationMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyModality whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyModality whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyModality whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyModality whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyModality whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyModality whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyModality wherePreparationInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyModality whereRequiresContrast($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyModality whereRequiresPreparation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyModality whereUpdatedAt($value)
 */
	class RadiologyModality extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $order_number
 * @property int $patient_id
 * @property int $doctor_id
 * @property int|null $appointment_id
 * @property \Illuminate\Support\Carbon $order_date
 * @property \Illuminate\Support\Carbon|null $scheduled_at
 * @property string|null $clinical_history
 * @property string|null $clinical_indication
 * @property string $priority
 * @property string $status
 * @property numeric $total_amount
 * @property numeric $discount
 * @property numeric $net_amount
 * @property numeric $paid_amount
 * @property string $payment_status
 * @property bool $report_delivered
 * @property \Illuminate\Support\Carbon|null $report_delivered_at
 * @property string|null $delivered_to
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Appointment|null $appointment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RadiologyConsent> $consents
 * @property-read int|null $consents_count
 * @property-read \App\Models\Doctor|null $doctor
 * @property-read float $balance_due
 * @property-read string $formatted_balance
 * @property-read string $formatted_net
 * @property-read string $formatted_total
 * @property-read string $priority_color
 * @property-read string $status_color
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RadiologyOrderItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Patient|null $patient
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder byPatient($patientId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder byPriority($priority)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder byStatus($status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder inProgress()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder paid()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder reportDelivered()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder reportPending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder scheduled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder stat()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder thisMonth()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder thisWeek()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder today()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder unpaid()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder urgent()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder whereAppointmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder whereClinicalHistory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder whereClinicalIndication($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder whereDeliveredTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder whereNetAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder whereOrderDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder wherePaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder whereReportDelivered($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder whereReportDeliveredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder whereScheduledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder withCritical()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrder withoutTrashed()
 */
	class RadiologyOrder extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $radiology_order_id
 * @property int $radiology_exam_id
 * @property numeric $price
 * @property numeric $discount
 * @property numeric $final_price
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $scanned_at
 * @property string|null $technician_name
 * @property string|null $equipment_used
 * @property bool $contrast_used
 * @property string|null $contrast_agent
 * @property numeric|null $contrast_dose_ml
 * @property bool $contrast_reaction
 * @property string|null $contrast_reaction_notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\RadiologyExam $exam
 * @property-read string $formatted_price
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RadiologyImage> $images
 * @property-read int|null $images_count
 * @property-read \App\Models\RadiologyOrder|null $order
 * @property-read \App\Models\RadiologyReport|null $report
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrderItem byStatus($status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrderItem completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrderItem whereContrastAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrderItem whereContrastDoseMl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrderItem whereContrastReaction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrderItem whereContrastReactionNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrderItem whereContrastUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrderItem whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrderItem whereEquipmentUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrderItem whereFinalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrderItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrderItem whereRadiologyExamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrderItem whereRadiologyOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrderItem whereScannedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrderItem whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrderItem whereTechnicianName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrderItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyOrderItem withContrast()
 */
	class RadiologyOrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $radiology_order_item_id
 * @property string|null $findings
 * @property string|null $impression
 * @property string|null $recommendations
 * @property string|null $comparison
 * @property bool $is_critical
 * @property string|null $critical_notes
 * @property \Illuminate\Support\Carbon|null $critical_notified_at
 * @property string|null $critical_notified_to
 * @property string $status
 * @property int|null $reported_by
 * @property \Illuminate\Support\Carbon|null $reported_at
 * @property int|null $verified_by
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property bool $is_verified
 * @property string|null $amendment_reason
 * @property int|null $amended_by
 * @property \Illuminate\Support\Carbon|null $amended_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $amendedBy
 * @property-read \App\Models\RadiologyOrderItem $orderItem
 * @property-read \App\Models\User|null $reportedBy
 * @property-read \App\Models\User|null $verifiedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport critical()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport draft()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport pendingVerification()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport verified()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport whereAmendedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport whereAmendedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport whereAmendmentReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport whereComparison($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport whereCriticalNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport whereCriticalNotifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport whereCriticalNotifiedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport whereFindings($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport whereImpression($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport whereIsCritical($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport whereRadiologyOrderItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport whereRecommendations($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport whereReportedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport whereReportedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport whereVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RadiologyReport whereVerifiedBy($value)
 */
	class RadiologyReport extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $employee_id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property bool $is_active
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Employee|null $employee
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $ward_code
 * @property string $type
 * @property int $total_beds
 * @property string|null $floor
 * @property string|null $block
 * @property numeric $bed_charges
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Bed> $beds
 * @property-read int|null $beds_count
 * @property-read mixed $available_beds_count
 * @property-read mixed $occupancy_percent
 * @property-read mixed $occupied_beds_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ward active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ward newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ward newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ward query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ward whereBedCharges($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ward whereBlock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ward whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ward whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ward whereFloor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ward whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ward whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ward whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ward whereTotalBeds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ward whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ward whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ward whereWardCode($value)
 */
	class Ward extends \Eloquent {}
}

