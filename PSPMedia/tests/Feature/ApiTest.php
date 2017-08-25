<?php

namespace Tests\Feature;

use App\Http\Models\CustomerModel;
use App\Http\Models\DepositModel;
use App\Http\Models\WithdrawModel;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiTest extends TestCase
{
    use DatabaseMigrations;

    public $genders = [
        'male', 'female'
    ];

    public $countries = [
        "Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe"
    ];


    public function testCreate()
    {
        $gender   = $this->genders[array_rand($this->genders)];
        $country  = $this->countries[array_rand($this->countries)];

        $data     = [
            'gender'        => $gender,
            'first_name'    => 'John',
            'last_name'     => 'Doe',
            'email'         => 'example@gmail.com',
            'country'       => $country
        ];

        $response = $this->json('POST', '/api/customer/create', $data);

        $response
            ->assertStatus(204);

        $this->assertDatabaseHas('customer', $data);
    }


    public function testCreateNotUniqueEmail()
    {
        factory(CustomerModel::class)->create([
            'email' => 'example@gmail.com'
        ]);
        $gender   = $this->genders[array_rand($this->genders)];
        $country  = $this->countries[array_rand($this->countries)];

        $data     = [
            'gender'        => $gender,
            'first_name'    => 'John',
            'last_name'     => 'Doe',
            'email'         => 'example@gmail.com',
            'country'       => $country
        ];

        $response = $this->json('POST', '/api/customer/create', $data);

        $response
            ->assertStatus(422);
    }


    public function testEdit()
    {
        $customer = factory(CustomerModel::class)->create();
        $gender   = $this->genders[array_rand($this->genders)];
        $country  = $this->countries[array_rand($this->countries)];

        $data     = [
            'customer_id'   => $customer['customer_id'],
            'gender'        => $gender,
            'first_name'    => 'John',
            'last_name'     => 'Doe',
            'email'         => 'example@gmail.com',
            'country'       => $country
        ];

        $response = $this->json('POST', '/api/customer/edit', $data);

        $response
            ->assertStatus(204);


        $this->assertDatabaseHas('customer', $data);
    }


    public function testDeposit()
    {
        $customer = factory(CustomerModel::class)->create();

        $data = [
            'customer_id'   => $customer->customer_id,
            'real_amount'   => rand(1, 999)
        ];

        $response = $this->json('POST', '/api/deposit/add', $data);

        $data['bonus_amount'] = $data['real_amount'] / 10;

        $response
            ->assertStatus(204);


        $this->assertDatabaseHas('deposit', $data);
    }


    public function testWithdraw()
    {
        $customer = factory(CustomerModel::class)->create();
        $deposit  = factory(DepositModel::class)->create([
            'customer_id' => $customer->customer_id
        ]);

        $data = [
            'customer_id'   => $customer->customer_id,
            'amount'        => rand(1, $deposit->real_amount)
        ];

        $response = $this->json('POST', '/api/withdraw/add', $data);

        $response
            ->assertStatus(204);


        $this->assertDatabaseHas('Withdraw', $data);
    }


    public function testWithdrawOver()
    {
        $customer = factory(CustomerModel::class)->create();
        $deposit  = factory(DepositModel::class)->create([
            'customer_id' => $customer->customer_id
        ]);

        $data = [
            'customer_id'   => $customer->customer_id,
            'amount'        => $deposit->real_amount + 1
        ];

        $response = $this->json('POST', '/api/withdraw/add', $data);

        $response
            ->assertStatus(422);
    }


    public function testReport()
    {
        $customers = [];
        for ($i = 0; $i < rand(30, 70); $i++) {
            $customers[] = factory(CustomerModel::class)->create();
        }

        $test      = [];
        $deposits  = [];
        $withdraws = [];
        foreach ($customers as $customer) {
            for ($i = 0; $i < rand(0, 10); $i++) {
                $deposit  = factory(DepositModel::class)->create([
                    'customer_id' => $customer->customer_id
                ]);

                $deposits[] = $deposits;

                $test[Carbon::createFromFormat('Y-m-d H:i:s', $deposit['created_at'])->toDateString()][$customer['country']] = [
                    'total_deposit_amount'              => (isset($test[Carbon::createFromFormat('Y-m-d H:i:s', $deposit['created_at'])->toDateString()][$customer['country']]['total_deposit_amount'])? $test[Carbon::createFromFormat('Y-m-d H:i:s', $deposit['created_at'])->toDateString()][$customer['country']]['total_deposit_amount'] + $deposit['real_amount'] : $deposit['real_amount'])
                ];
            }

            for ($i = 0; $i < rand (0, 10); $i++) {
                $real_amount = DepositModel::where('customer_id', $customer->customer_id)->sum('real_amount');
                $amount = rand(1, 999);

                if ($amount <= $real_amount) {
                    $withdraws[] = factory(WithdrawModel::class)->create([
                        'customer_id' => $customer->customer_id
                    ]);


                }
            }
        }

//        2017-08-23 Hungary 489 deposit amount

        $data = [
            'from'  => Carbon::now()->subDays(7)->toDateString(),
            'to'    => Carbon::now()->toDateString()
        ];

        $response = $this->json('GET', '/api/report', $data);

        $report = json_decode($response->getContent());

        foreach ($report as $elements) {
//            var_dump('TEST DATA \n');
//            var_dump($test[$elements->date][$elements->country]['total_deposit_amount']);
//
//            var_dump('REAL DATA \n');
//            var_dump($elements->total_deposit_amount);
//
//            var_dump('country \n');
//            var_dump($elements->country);
//
//            var_dump('date \n');
//            var_dump($elements->date);
            $this->assertEquals($test[$elements->date][$elements->country]['total_deposit_amount'], $elements->total_deposit_amount);
        }
    }
}
