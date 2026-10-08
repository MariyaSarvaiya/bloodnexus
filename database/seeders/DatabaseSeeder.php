<?php

namespace Database\Seeders;

use App\Models\BloodInventoryTransaction;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\SecurityLog;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Demo data for the BloodNexus presentation/demo environment.
     * Creates 5 Blood Need users + 5 Donors, donor medical histories,
     * one week of login activity and completed donations so the Admin
     * Reporting Center has meaningful data immediately after seeding.
     */
    public function run(): void
    {
        $password = 'Password@123';

        $users = [
            ['name'=>'Rahul Patel','email'=>'rahul.user@bloodnexus.test','phone'=>'9876500001','blood_group'=>'A+','city'=>'Ahmedabad','area'=>'Satellite'],
            ['name'=>'Priya Shah','email'=>'priya.user@bloodnexus.test','phone'=>'9876500002','blood_group'=>'O+','city'=>'Ahmedabad','area'=>'Vastrapur'],
            ['name'=>'Amit Mehta','email'=>'amit.user@bloodnexus.test','phone'=>'9876500003','blood_group'=>'B+','city'=>'Gandhinagar','area'=>'Sector 21'],
            ['name'=>'Neha Desai','email'=>'neha.user@bloodnexus.test','phone'=>'9876500004','blood_group'=>'AB+','city'=>'Ahmedabad','area'=>'Maninagar'],
            ['name'=>'Kunal Joshi','email'=>'kunal.user@bloodnexus.test','phone'=>'9876500005','blood_group'=>'O-','city'=>'Vadodara','area'=>'Alkapuri'],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(['email'=>$data['email']], array_merge($data, [
                'password'=>Hash::make($password), 'role'=>'user', 'email_verified_at'=>now(),
            ]));
        }

        $donorData = [
            ['name'=>'Arjun Patel','email'=>'arjun.donor@bloodnexus.test','phone'=>'9876510001','blood_group'=>'O+','city'=>'Ahmedabad','area'=>'Navrangpura','address'=>'Navrangpura, Ahmedabad','medical_history'=>json_encode(['chronic_condition'=>'no','regular_medicines'=>'no','allergies'=>'no','recent_surgery'=>'no','fever_infection'=>'no','blood_transfusion'=>'no','tattoo_piercing'=>'no','recent_donation'=>'no'])],
            ['name'=>'Meera Shah','email'=>'meera.donor@bloodnexus.test','phone'=>'9876510002','blood_group'=>'A+','city'=>'Ahmedabad','area'=>'Bodakdev','address'=>'Bodakdev, Ahmedabad','medical_history'=>json_encode(['chronic_condition'=>'no','regular_medicines'=>'no','allergies'=>'no','recent_surgery'=>'no','fever_infection'=>'no','blood_transfusion'=>'no','tattoo_piercing'=>'no','recent_donation'=>'no'])],
            ['name'=>'Dev Mehta','email'=>'dev.donor@bloodnexus.test','phone'=>'9876510003','blood_group'=>'B+','city'=>'Gandhinagar','area'=>'Sector 11','address'=>'Sector 11, Gandhinagar','medical_history'=>json_encode(['chronic_condition'=>'no','regular_medicines'=>'no','allergies'=>'no','recent_surgery'=>'no','fever_infection'=>'no','blood_transfusion'=>'no','tattoo_piercing'=>'no','recent_donation'=>'no'])],
            ['name'=>'Riya Desai','email'=>'riya.donor@bloodnexus.test','phone'=>'9876510004','blood_group'=>'AB+','city'=>'Ahmedabad','area'=>'Prahlad Nagar','address'=>'Prahlad Nagar, Ahmedabad','medical_history'=>json_encode(['chronic_condition'=>'no','regular_medicines'=>'no','allergies'=>'no','recent_surgery'=>'no','fever_infection'=>'no','blood_transfusion'=>'no','tattoo_piercing'=>'no','recent_donation'=>'no'])],
            ['name'=>'Harsh Joshi','email'=>'harsh.donor@bloodnexus.test','phone'=>'9876510005','blood_group'=>'O-','city'=>'Vadodara','area'=>'Gotri','address'=>'Gotri, Vadodara','medical_history'=>json_encode(['chronic_condition'=>'no','regular_medicines'=>'no','allergies'=>'no','recent_surgery'=>'no','fever_infection'=>'no','blood_transfusion'=>'no','tattoo_piercing'=>'no','recent_donation'=>'no'])],
        ];

        $donors=[];
        foreach ($donorData as $data) {
            $user=User::updateOrCreate(['email'=>$data['email']], [
                'name'=>$data['name'],'phone'=>$data['phone'],'blood_group'=>$data['blood_group'],
                'city'=>$data['city'],'area'=>$data['area'],'password'=>Hash::make($password),
                'role'=>'donor','email_verified_at'=>now(),
            ]);
            $donors[] = Donor::updateOrCreate(['user_id'=>$user->id], array_merge($data, [
                'user_id'=>$user->id,'is_available'=>true,'last_donation_date'=>null,
            ]));
        }

        // Remove only previous demo audit rows/requests created by this seeder.
        SecurityLog::where('message','like','DEMO SEED%')->delete();
        BloodRequest::where('message','like','DEMO SEED%')->delete();

        // One-week login activity: repeated real-looking events for the demo accounts.
        $loginUsers = collect($users)->map(fn($d)=>User::where('email',$d['email'])->first())
            ->merge(collect($donorData)->map(fn($d)=>User::where('email',$d['email'])->first()));
        foreach ($loginUsers->values() as $i=>$user) {
            foreach ([1,3,6] as $offset) {
                $when=now()->subDays($offset)->setTime(9 + (($i*2+$offset)%9), 15 + (($i*7)%40), ($i*11)%60);
                SecurityLog::record([
                    'user_id'=>$user->id,'event_type'=>'successful_login','severity'=>'info','risk_score'=>0,
                    'ip_address'=>'127.0.0.1','user_agent'=>'BloodNexus Demo Browser','route'=>'login','method'=>'POST',
                    'message'=>'DEMO SEED successful login activity.','metadata'=>['demo_seed'=>true,'role'=>$user->role],
                    'created_at'=>$when,'updated_at'=>$when,
                ]);
            }
        }

        // Completed donation history for the report. One unit per donor on different days.
        $patients = [
            ['name'=>'Aarav Shah','city'=>'Ahmedabad','area'=>'Satellite','hospital'=>'City Care Hospital','blood'=>'O+','user'=>'rahul.user@bloodnexus.test'],
            ['name'=>'Diya Patel','city'=>'Ahmedabad','area'=>'Vastrapur','hospital'=>'Lifeline Hospital','blood'=>'A+','user'=>'priya.user@bloodnexus.test'],
            ['name'=>'Kabir Mehta','city'=>'Gandhinagar','area'=>'Sector 21','hospital'=>'Shanti Multispeciality','blood'=>'B+','user'=>'amit.user@bloodnexus.test'],
            ['name'=>'Anaya Desai','city'=>'Ahmedabad','area'=>'Maninagar','hospital'=>'Hope Hospital','blood'=>'AB+','user'=>'neha.user@bloodnexus.test'],
            ['name'=>'Vihaan Joshi','city'=>'Vadodara','area'=>'Alkapuri','hospital'=>'Sahyog Hospital','blood'=>'O-','user'=>'kunal.user@bloodnexus.test'],
            ['name'=>'Ishaan Shah','city'=>'Ahmedabad','area'=>'Bodakdev','hospital'=>'Metro Heart Centre','blood'=>'O+','user'=>'rahul.user@bloodnexus.test'],
            ['name'=>'Sara Patel','city'=>'Gandhinagar','area'=>'Sector 11','hospital'=>'Green Cross Hospital','blood'=>'B+','user'=>'amit.user@bloodnexus.test'],
            ['name'=>'Reyansh Desai','city'=>'Ahmedabad','area'=>'Prahlad Nagar','hospital'=>'Sterling Care','blood'=>'AB+','user'=>'neha.user@bloodnexus.test'],
        ];

        foreach ($patients as $i=>$patient) {
            $donor=$donors[$i % count($donors)];
            $when=now()->subDays(6-$i)->setTime(10 + ($i%7), 10 + ($i*5)%45, 0);
            $request=BloodRequest::create([
                'user_id'=>User::where('email',$patient['user'])->value('id'),
                'donor_id'=>$donor->id,
                'patient_name'=>$patient['name'],
                'requester_type'=>'self','request_for'=>'self',
                'blood_group'=>$patient['blood'],'city'=>$patient['city'],'area'=>$patient['area'],
                'hospital'=>$patient['hospital'],'contact'=>$patient['name'],'contact_phone'=>$donor->phone,
                'units'=>1,'units_fulfilled'=>1,'urgency'=>$i%3===0?'urgent':'normal','emergency_mode'=>false,
                'message'=>'DEMO SEED completed donation report record.','reason'=>'Regular blood requirement',
                'donation_date'=>$when->toDateString(),'donation_time'=>$when->format('H:i:s'),
                'completed_at'=>$when,'fulfilled_at'=>$when,'closure_reason'=>'Demo data seeded for reporting.',
                'status'=>'completed','created_at'=>$when->copy()->subHours(4),'updated_at'=>$when,
            ]);
            BloodInventoryTransaction::create([
                'type'=>'received','blood_group'=>$patient['blood'],'units'=>1,'donor_id'=>$donor->id,'blood_request_id'=>$request->id,
                'transaction_at'=>$when,'source'=>'Demo donor donation','note'=>'DEMO SEED: blood received from donor.',
                'created_at'=>$when,'updated_at'=>$when,
            ]);
            BloodInventoryTransaction::create([
                'type'=>'issued','blood_group'=>$patient['blood'],'units'=>1,'donor_id'=>$donor->id,'blood_request_id'=>$request->id,
                'transaction_at'=>$when->copy()->addMinutes(35),'source'=>'Demo blood need fulfillment','note'=>'DEMO SEED: blood issued to fulfilled need.',
                'created_at'=>$when->copy()->addMinutes(35),'updated_at'=>$when->copy()->addMinutes(35),
            ]);
        }

        // Keep seeded donor profiles informative after demo completion history.
        foreach ($donors as $donor) {
            $latest=BloodRequest::where('donor_id',$donor->id)->where('status','completed')->latest('donation_date')->first();
            if ($latest) $donor->update(['last_donation_date'=>$latest->donation_date,'is_available'=>false]);
        }

        $this->command?->info('BloodNexus demo data ready: 5 Blood Need + 5 Donor accounts, medical histories, weekly login activity and blood-flow records.');
        $this->command?->info('Password for all 10 demo accounts: '.$password);
    }
}
