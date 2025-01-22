<?php

namespace Modules\Fees\Database\Seeders;

use App\Models\StudentRecord;
use Illuminate\Database\Seeder;
use Modules\Fees\Entities\FmFeesType;
use Modules\Fees\Entities\FmFeesGroup;
use Illuminate\Database\Eloquent\Model;
use Modules\Fees\Entities\FmFeesInvoice;
use Modules\Fees\Entities\FmFeesInvoiceChield;

class FmFeesInvoiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $academic_id, $count = 5)
    {
        $school_academic = ['school_id' => $school_id, 'academic_id' => $academic_id];
        $smStudentIds = StudentRecord::select('id', 'class_id', 'student_id')->get();

        FmFeesGroup::factory()->times($count)->create($school_academic)->each(function ($feesGroup) use ($school_academic, $smStudentIds) {
            FmFeesType::factory()->times(5)->create(array_merge([
                'fees_group_id' => $feesGroup->id,
            ], $school_academic))->each(function ($feesTypes) use ($school_academic, $smStudentIds) {
                foreach ($smStudentIds as $id) {
                    FmFeesInvoice::factory()->times(1)->create(array_merge([
                        'invoice_id' => generateRandomString(15),
                        'student_id' => $id->id,
                        'class_id' => $id->class_id,
                    ], $school_academic))->each(function ($feesInvoices) use ($school_academic, $feesTypes) {
                        FmFeesInvoiceChield::factory()->times(1)->create(array_merge([
                            'fees_invoice_id' => $feesInvoices->id,
                            'fees_type' => $feesTypes->id,
                        ], $school_academic));
                    });
                }
            });
        });
    }
}
