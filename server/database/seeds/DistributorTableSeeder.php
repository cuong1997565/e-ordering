<?php

use Illuminate\Database\Seeder;
use App\Models\Distributor;

class DistributorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        factory(Distributor::class, 10)->create();
          $data = [
              [
                  'name' => 'Công ty TNHH TM Hưng Tín',
                  'email' => 'test1+1@hiworld.com.vn',
                  'address' => '351 - Đường Nguyễn Huệ - P. Phố Mới - TP Lào Cai, Lào Cai, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'HT03',
                  'tax_code' => '5300221665',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty TNHH H&T Thành Tín',
                  'email' => 'test1+2@hiworld.com.vn',
                  'address' => 'Số 033, Đường Hợp Thành, Phường  Phố Mới, Thành Phố Lào Cai, Tỉnh Lào Cai, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'TT24',
                  'tax_code' => '5300621744',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty TNHH Mạnh Huyền Điện Biên',
                  'email' => 'test1+3@hiworld.com.vn',
                  'address' => 'Số nhà 25A- Tổ dân phố 9- phường Nam Thanh- Thành phố Điện Biên Phủ- Tỉnh Điện Biên, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'MH08',
                  'tax_code' => '5600285483',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty TNHH Xuân Đường Điện Biên',
                  'email' => 'test1+4@hiworld.com.vn',
                  'address' => 'Số nhà 80, Tổ dân phố 9, Phường Nam Thanh, TP Điện Biên Phủ, Tỉnh Điện Biên, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'NDB04',
                  'tax_code' => '6200069810',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty TNHH Hải Hương Mộc Châu',
                  'email' => 'test1+5@hiworld.com.vn',
                  'address' => 'Tiểu khu Nhà Nghỉ, Thị trấn Nt Mộc Châu, Huyện Mộc Châu, Tỉnh Sơn La, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'HH17',
                  'tax_code' => '5500593798',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty TNHH Tâm Phúc',
                  'email' => 'test1+6@hiworld.com.vn',
                  'address' => 'Số 11 - Tổ 1 - P. Tô Hiệu - TP Sơn La, Sơn La, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'TP06',
                  'tax_code' => '5500593798',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty TNHH Thương Mại Châu Tuấn',
                  'email' => 'test1+7@hiworld.com.vn',
                  'address' => 'Phường Tân Phong - TP Lai Châu, LAI CHÂU, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'CT04',
                  'tax_code' => '6200000262',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty TNHH Một Thành Viên Thương Mại Hà Tĩnh',
                  'email' => 'test1+8@hiworld.com.vn',
                  'address' => 'Số 241, Tổ 21, Phường Minh Khai, Thành phố Hà Giang, Tỉnh Hà Giang, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'HT18',
                  'tax_code' => '5100248927',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty TNHH nội thất Việt',
                  'email' => 'test1+9@hiworld.com.vn',
                  'address' => 'Tổ 10 - P.Nguyễn Trãi - TX Hà Giang,  Hà Giang, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'NT020',
                  'tax_code' => '5100247070',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty TNHH Thương Mại Châu Tuấn',
                  'email' => 'test1+10@hiworld.com.vn',
                  'address' => 'Số 11-Đường Lê Lợi- Phường Vĩnh Trại- Thành Phố Lạng Sơn- Tỉnh Lạng Sơn, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'HP020',
                  'tax_code' => '6200000262',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty TNHH MTV Phương Lê',
                  'email' => 'test1+11@hiworld.com.vn',
                  'address' => '215 Bắc Sơn - P.Hoàng Văn Thụ - TP Lạng Sơn,  Lạng Sơn, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'PL02',
                  'tax_code' => '4900301286',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty TNHH nội thất Việt',
                  'email' => 'test1+12@hiworld.com.vn',
                  'address' => 'Tổ 10 - P.Nguyễn Trãi - TX Hà Giang,  Hà Giang, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'NT02',
                  'tax_code' => '5100247070',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty TNHH MTV Hồng Phái',
                  'email' => 'test1+13@hiworld.com.vn',
                  'address' => 'Số 11-Đường Lê Lợi- Phường Vĩnh Trại- Thành Phố Lạng Sơn- Tỉnh Lạng Sơn, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'HP02',
                  'tax_code' => '4900645985',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty TNHH MTV Phương Lê',
                  'email' => 'test1+14@hiworld.com.vn',
                  'address' => 'Bắc Sơn - P.Hoàng Văn Thụ - TP Lạng Sơn,  Lạng Sơn, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'PL020',
                  'tax_code' => '4900301286',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Hộ kinh doanh Ma Thị Luyến',
                  'email' => 'test1+15@hiworld.com.vn',
                  'address' => 'Thị trấn Chợ Rã, huyện Ba Bể, Tỉnh Bắc Kạn, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'MTL01',
                  'tax_code' => '4700102283',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty Cổ Phần Dịch Vụ Và Thương Mại 5-10',
                  'email' => 'test1+16@hiworld.com.vn',
                  'address' => 'Tổ 9B P. Đức Xuân - TP Bắc Kạn, Bắc Kạn, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'TM03',
                  'tax_code' => '4700148785',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty TNHH Đầu tư và Thương mại Dũng Hà',
                  'email' => 'test1+17@hiworld.com.vn',
                  'address' => 'Số nhà 027, Tổ 10, Phường Sông Hiến, Thành phố Cao Bằng, Tỉnh Cao Bằng, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'DH100',
                  'tax_code' => '4800803130',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty Trách Nhiệm Hữu Hạn Lan Sơn',
                  'email' => 'test1+18@hiworld.com.vn',
                  'address' => 'Đường Quang Trung, Tổ 16, Phường Phan Thiết, Thành phố Tuyên Quang, Tỉnh Tuyên Quang, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'LS01',
                  'tax_code' => '5000225789',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty Trách Nhiệm Hữu Hạn Hoàng Vân Tuyên Quang',
                  'email' => 'test1+19@hiworld.com.vn',
                  'address' => 'Tổ 28 - P.Phan Thiết - TP.Tuyên Quang, Tuyên Quang, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'DH10',
                  'tax_code' => '4800803130',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty Trách Nhiệm Hữu Hạn Lan Sơn',
                  'email' => 'test1+20@hiworld.com.vn',
                  'address' => 'Đường Quang Trung, Tổ 16, Phường Phan Thiết, Thành phố Tuyên Quang, Tỉnh Tuyên Quang, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'TQ01',
                  'tax_code' => '5000234920',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty TNHH Hòa Bình',
                  'email' => 'test1+21@hiworld.com.vn',
                  'address' => 'Số nhà 349, tổ dân phố số 15, phường Nguyễn Thái Học, Thành phố Yên Bái, Tỉnh Yên Bái, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'HB01',
                  'tax_code' => '5200131489',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty cổ phần Đồng Tâm Yên Bái',
                  'email' => 'test1+22@hiworld.com.vn',
                  'address' => 'Số nhà 70, đường Nguyễn Thái Học, phường Nguyễn Thái Học, TP Yên Bái, tỉnh Yên Bái, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'DT12',
                  'tax_code' => '5200746080',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty TNHH MTV Thương Mại Và Dịch Vụ Dũng Thảo',
                  'email' => 'test1+23@hiworld.com.vn',
                  'address' => 'Tổ 11 - P.Quán Triều - TP.Thái Nguyên, Thái Nguyên, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'DT04',
                  'tax_code' => '4600418798',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty TNHH Nguyễn Thái Family',
                  'email' => 'test1+24@hiworld.com.vn',
                  'address' => 'Số 516, Tổ 18, Phường Gia Sàng, Thành phố Thái Nguyên, Tỉnh Thái Nguyên, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'NT11',
                  'tax_code' => '4600459321',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty TNHH Ân Hường',
                  'email' => 'test1+25@hiworld.com.vn',
                  'address' => 'Tổ dân phố 4A- phường Phố Cò- Thành phố Sông Công- Tỉnh Thái Nguyên, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'AH01',
                  'tax_code' => '4600792499',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty TNHH Thân Phong',
                  'email' => 'test1+26@hiworld.com.vn',
                  'address' => 'Số 191, đường Hoàng Văn Thụ, Phường Phan Đình Phùng, Thành phố Thái Nguyên, Tỉnh Thái Nguyên, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'TP24',
                  'tax_code' => '4600447213',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty Cổ Phần Phát Triển Hòa Bình',
                  'email' => 'test1+27@hiworld.com.vn',
                  'address' => 'Tổ 23-Khu 5, phường Thanh Miếu - Việt Trì - Phú Thọ, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'HB02',
                  'tax_code' => '2600424685',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty TNHH Hưng Yến',
                  'email' => 'test1+28@hiworld.com.vn',
                  'address' => 'Khu 3 Thị trấn Thanh Ba - Thanh Ba - Phú Thọ, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'HY01',
                  'tax_code' => '2600342947',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty cổ phần đầu tư và phát triển Mỹ Việt',
                  'email' => 'test1+29@hiworld.com.vn',
                  'address' => 'Xã Định Trung, Thành phố Vĩnh Yên, Tỉnh Vĩnh Phúc, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'MV01',
                  'tax_code' => '2500219467',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty TNHH Trường Phú VP',
                  'email' => 'test1+30@hiworld.com.vn',
                  'address' => 'Số 8, Ngõ 2 đường Ngô Gia Tự, Phường Khai Quang, Thành phố Vĩnh Yên, Tỉnh Vĩnh Phúc, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'TP27',
                  'tax_code' => '2500615076',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty Cổ Phần Đầu Tư Quốc Anh',
                  'email' => 'test1+31@hiworld.com.vn',
                  'address' => 'Số nhà 662 Đường Hà Huy Tập - xã Yên Viên - Huyện Gia Lâm - TP Hà Nội, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'QA01',
                  'tax_code' => '0106162787',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty TNHH Ceramics Hoàng Minh Phúc',
                  'email' => 'test1+32@hiworld.com.vn',
                  'address' => 'Số 1, Ngõ 484 Đường Hà Huy Tập, Thị trấn Yên Viên, Huyện Gia Lâm, Thành phố Hà Nội, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'HM04',
                  'tax_code' => '0108445269',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty TNHH Thương Mại Sản Xuất Và Dịch Vụ Thành Trung',
                  'email' => 'test1+33@hiworld.com.vn',
                  'address' => 'Số 54 đường Tây Hồ, Phường Quảng An, Quận Tây Hồ, Thành phố Hà Nội, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'TT02',
                  'tax_code' => '0103615615',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty TNHH Thương mại Trần Gia Khánh',
                  'email' => 'test1+34@hiworld.com.vn',
                  'address' => 'Số 38, đường Thanh Nhàn, Phường Thanh Nhàn, Quận Hai Bà Trưng, Thành phố Hà Nội, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'TG05',
                  'tax_code' => '0107445604',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => ' BT07	Công ty TNHH Bảo Tiến - Cao Minh',
                  'email' => 'test1+35@hiworld.com.vn',
                  'address' => 'số 11, tổ 21 cụm 6, Phường Hạ Đình, Quận Thanh Xuân, Thành Phố Hà Nội, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'BT07',
                  'tax_code' => '0100906856',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty TNHH Xây Dựng và Phát Triển Thương Mại Cao Minh',
                  'email' => 'test1+36@hiworld.com.vn',
                  'address' => 'Số 11, Tổ 21, Cụm 6, Phường Hạ Đình, Quận Thanh Xuân, Thành phố Hà Nội, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'CM03',
                  'tax_code' => '0108827934',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => ' Công ty TNHH Hữu Thảo',
                  'email' => 'test1+37@hiworld.com.vn',
                  'address' => '25 Lạc Trung - Hai Bà Trưng, Hà Nội, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'HT07',
                  'tax_code' => '0101193132',
                  'contact_person' => 'test1',
                  'active' => 1
              ],
              [
                  'name' => 'Công ty TNHH H.T.A',
                  'email' => 'test1+38@hiworld.com.vn',
                  'address' => 'KM 2 - Quốc Lộ 21B Phú Lãm - Hà Đông, Hà Nội, Việt Nam',
                  'phone' => '0123456789',
                  'area_id' => '3',
                  'code' => 'HT05',
                  'tax_code' => '0500555218',
                  'contact_person' => 'test1',
                  'active' => 1
              ]
          ];

            DB::table('distributors')->insert($data); // Query Builder approach



    }
}

