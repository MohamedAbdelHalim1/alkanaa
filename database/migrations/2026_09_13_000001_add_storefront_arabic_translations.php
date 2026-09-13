<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds the Arabic ('sa') text for storefront strings that only had an English
 * row, so the Arabic site stops showing English labels ("View Order History",
 * "Continue Shopping"...).
 *
 * Only inserts where no 'sa' row exists: translations already edited from the
 * admin panel are never overwritten. Keys are built exactly like translate()
 * in app/Http/Helpers.php.
 */
return new class extends Migration
{
    private function key(string $text): string
    {
        return preg_replace('/[^A-Za-z0-9\_]/', '', str_replace(' ', '_', strtolower($text)));
    }

    private function strings(): array
    {
        return [
            ' first to continue' => ' أولاً للمتابعة',
            ' Product Queries ' => ' استفسارات المنتج ',
            ' to submit your questions to seller' => ' لإرسال أسئلتك إلى البائع',
            'A fresh verification link has been sent to your email address.' => 'تم إرسال رابط تحقق جديد إلى بريدك الإلكتروني.',
            'Account Name' => 'اسم الحساب',
            'Account Number' => 'رقم الحساب',
            'Add more files' => 'إضافة ملفات أخرى',
            'Adding more files' => 'جارٍ إضافة الملفات',
            'Additional Info' => 'معلومات إضافية',
            'After deleting your account, wallet balance will no longer in our system' => 'بعد حذف حسابك لن يبقى رصيد المحفظة في نظامنا',
            'All Cupons' => 'كل الكوبونات',
            'And Up' => 'وأكثر',
            'Ask about this product' => 'اسأل عن هذا المنتج',
            'Available Now' => 'متوفر الآن',
            'Before proceeding, please check your email for a verification link. If you did not receive the email.' => 'قبل المتابعة، تحقق من بريدك الإلكتروني للعثور على رابط التحقق. إذا لم يصلك البريد.',
            'Best price guaranteed' => 'أفضل سعر مضمون',
            'Between you and' => 'بينك وبين',
            'Blog' => 'المدونة',
            'Blogs' => 'المقالات',
            'Call Us' => 'اتصل بنا',
            'Cancel upload' => 'إلغاء الرفع',
            'Carrier' => 'شركة الشحن',
            'Change' => 'تغيير',
            'Change Coupon' => 'تغيير الكوبون',
            'Coming Soon' => 'قريباً',
            'Compare Products' => 'مقارنة المنتجات',
            'Complete' => 'مكتمل',
            'Conversations With ' => 'المحادثات مع ',
            'Convert Club Points' => 'تحويل نقاط النادي',
            'Copy coupon Code' => 'نسخ كود الكوبون',
            'Copy the Code' => 'نسخ الكود',
            'Coupon Code Copied' => 'تم نسخ كود الكوبون',
            'Coupons' => 'الكوبونات',
            'Current Package' => 'الباقة الحالية',
            'days of registration' => 'يوم من التسجيل',
            'Decrease' => 'تقليل',
            'Decrease quantity' => 'تقليل الكمية',
            'Default' => 'افتراضي',
            'Delete Account' => 'حذف الحساب',
            'Delete Selection' => 'حذف المحدد',
            'Delete Your Account' => 'احذف حسابك',
            'Deleting Account Means:' => 'حذف الحساب يعني:',
            'Discount on your Purchase Within' => 'خصم على مشترياتك خلال',
            'Download Your Products' => 'حمّل منتجاتك',
            'Drop files here, paste or' => 'اسحب الملفات هنا، الصقها أو',
            'Ended' => 'انتهى',
            'Enter a location' => 'أدخل الموقع',
            'Enter Amount' => 'أدخل المبلغ',
            'Enter Email' => 'أدخل البريد الإلكتروني',
            'Enter Name' => 'أدخل الاسم',
            'Enter Phone' => 'أدخل رقم الجوال',
            'Enter your email address and new password and confirm password.' => 'أدخل بريدك الإلكتروني وكلمة المرور الجديدة ثم أكّدها.',
            'Enter your email address or phone number to recover your password.' => 'أدخل بريدك الإلكتروني أو رقم جوالك لاستعادة كلمة المرور.',
            'Estimate Shipping Time' => 'مدة الشحن المتوقعة',
            'File selected' => 'تم اختيار ملف',
            'Files selected' => 'تم اختيار ملفات',
            'Filter by Availability' => 'التصفية حسب التوفر',
            'Follow Seller' => 'متابعة البائع',
            'Followed' => 'تتابعه',
            'Followed Sellers' => 'البائعون المتابَعون',
            'Free' => 'مجاناً',
            'Free Package' => 'باقة مجانية',
            'Frequently Bought Products' => 'منتجات تُشترى معاً غالباً',
            'Frequently Bought Together' => 'يُشترى معاً غالباً',
            'from' => 'من',
            'General Products' => 'المنتجات العامة',
            'has not been verified yet.' => 'لم يتم التحقق منه بعد.',
            'Have coupon code? Apply here' => 'لديك كود خصم؟ طبّقه هنا',
            'If you create any classified ptoducts, after deleting your account, those products will no longer in our system' => 'إذا أضفت منتجات مبوبة، فلن تبقى في نظامنا بعد حذف حسابك',
            'If you have already used the same mail address or phone number before, please ' => 'إذا سبق أن استخدمت نفس البريد الإلكتروني أو رقم الجوال، يرجى ',
            'Increase' => 'زيادة',
            'Increase quantity' => 'زيادة الكمية',
            'Item has been removed from cart' => 'تمت إزالة المنتج من السلة',
            'johndoe@example.com' => 'name@example.com',
            'Last Recharge' => 'آخر شحن',
            'Latitude' => 'خط العرض',
            'Less' => 'أقل',
            'Loading...' => 'جارٍ التحميل...',
            'login as a seller' => 'سجّل الدخول كبائع',
            'Longitude' => 'خط الطول',
            'Max Qty' => 'أقصى كمية',
            'Member Since' => 'عضو منذ',
            'Message us' => 'راسلنا',
            'Min Qty' => 'أقل كمية',
            'Min Spend ' => 'الحد الأدنى للشراء ',
            'Minimum Order Amount' => 'الحد الأدنى لقيمة الطلب',
            'More' => 'المزيد',
            'My Questions' => 'أسئلتي',
            'New Products' => 'منتجات جديدة',
            'No none asked to seller yet' => 'لم يسأل أحد البائع بعد',
            'No notification found' => 'لا توجد إشعارات',
            'No products found' => 'لا توجد منتجات',
            'Non verified seller' => 'بائع غير موثّق',
            'Non-refundable' => 'غير قابل للاسترجاع',
            'Not Applicable' => 'غير متاح',
            'Not Delivered Yet' => 'لم يتم التوصيل بعد',
            'Note: ' => 'ملاحظة: ',
            'Notification Deleted successfully' => 'تم حذف الإشعار بنجاح',
            'OFF' => 'خصم',
            'OFF on total orders' => 'خصم على إجمالي الطلبات',
            'OFF on total orders within' => 'خصم على إجمالي الطلبات خلال',
            'Offline Order Payment' => 'دفع الطلب يدوياً',
            'Offline Recharge Wallet' => 'شحن المحفظة يدوياً',
            'Ok. I Understood' => 'حسناً، فهمت',
            'Or, Your wallet balance :' => 'أو رصيد محفظتك:',
            'Other Questions' => 'أسئلة أخرى',
            'out of 5.0' => 'من 5.0',
            'Pause upload' => 'إيقاف الرفع مؤقتاً',
            'Phone no. (optional)' => 'رقم الجوال (اختياري)',
            'Pick a color' => 'اختر لوناً',
            'Pickip Point' => 'نقطة الاستلام',
            'Please Add Address' => 'يرجى إضافة عنوان',
            'Please choose a different address.' => 'يرجى اختيار عنوان مختلف.',
            'Please choose all the options' => 'يرجى اختيار كل الخيارات',
            'Please fill in all mandatory fields!' => 'يرجى تعبئة جميع الحقول المطلوبة!',
            'Please Login as a customer to add products to the Cart.' => 'يرجى تسجيل الدخول كعميل لإضافة المنتجات إلى السلة.',
            'Please Login as a customer to add products to the WishList.' => 'يرجى تسجيل الدخول كعميل لإضافة المنتجات إلى المفضلة.',
            'Please Login as a customer to apply coupon code.' => 'يرجى تسجيل الدخول كعميل لتطبيق كود الخصم.',
            'Premium Packages for Customers' => 'باقات مميزة للعملاء',
            'Previous' => 'السابق',
            'Processing' => 'قيد المعالجة',
            'Product Image' => 'صورة المنتج',
            'Product Inquiry' => 'استفسار عن منتج',
            'Product Unavailable' => 'المنتج غير متوفر',
            'Product Upload Remains' => 'المنتجات المتبقية للرفع',
            'Rate this Product' => 'قيّم هذا المنتج',
            'Ratings' => 'التقييمات',
            'Read Full Blog' => 'اقرأ المقال كاملاً',
            'Recent Posts' => 'أحدث المقالات',
            'Recharge Wallet' => 'شحن المحفظة',
            'Redeem point' => 'استبدال النقاط',
            'Refund Note' => 'ملاحظة الاسترجاع',
            'Register ' => 'سجّل ',
            'Rejected' => 'مرفوض',
            'Remove from wishlist' => 'إزالة من المفضلة',
            'Reorder' => 'إعادة الطلب',
            'Reset Password' => 'إعادة تعيين كلمة المرور',
            'Resume upload' => 'استئناف الرفع',
            'Retry upload' => 'إعادة محاولة الرفع',
            'Review' => 'تقييم',
            'Review Images' => 'صور التقييم',
            'Reviews & Ratings' => 'التقييمات والمراجعات',
            'Routing Number' => 'رقم التوجيه',
            'Search for products...' => 'ابحث عن منتج...',
            'Select a condition' => 'اختر الحالة',
            'Select a conversation to view all messages' => 'اختر محادثة لعرض كل الرسائل',
            'Select Nearest Pick-up Point' => 'اختر أقرب نقطة استلام',
            'Seller did not respond yet' => 'لم يرد البائع بعد',
            'Seller Guarantees' => 'ضمانات البائع',
            'Seller Message' => 'رسالة البائع',
            'Send Password Reset Code' => 'إرسال رمز إعادة تعيين كلمة المرور',
            'Send Reply' => 'إرسال الرد',
            'Service Fee' => 'رسوم الخدمة',
            'Shipping is not available to your selected address.' => 'الشحن غير متاح للعنوان الذي اخترته.',
            'Shopping Cart' => 'سلة التسوق',
            'Show size guide' => 'عرض دليل المقاسات',
            'Solved' => 'تم الحل',
            'Sorry, nothing found for' => 'عذراً، لا توجد نتائج لـ',
            'Standard' => 'قياسي',
            'Tell us about your query' => 'أخبرنا عن استفسارك',
            'These images are visible in product review page gallery. Upload square images' => 'تظهر هذه الصور في معرض تقييمات المنتج. ارفع صوراً مربعة',
            'this item' => 'هذا المنتج',
            'This offer has been expired.' => 'انتهت صلاحية هذا العرض.',
            'to get' => 'للحصول على',
            'Total Club Points' => 'إجمالي نقاط النادي',
            'Total Clubpoint' => 'إجمالي النقاط',
            'Total Expenditure' => 'إجمالي المصروفات',
            'Tracking code' => 'رمز التتبع',
            'Transit in' => 'يصل خلال',
            'Transit Time' => 'مدة التوصيل',
            'Type here...' => 'اكتب هنا...',
            'Unfollow Seller' => 'إلغاء متابعة البائع',
            'Unfollow This Seller' => 'إلغاء متابعة هذا البائع',
            'Upcoming' => 'قادم',
            'Upload complete' => 'اكتمل الرفع',
            'Upload paused' => 'تم إيقاف الرفع مؤقتاً',
            'Uploading' => 'جارٍ الرفع',
            'Use Phone Number Instead' => 'استخدم رقم الجوال بدلاً من ذلك',
            'Variation :' => 'الخيار:',
            'Verified' => 'موثّق',
            'Verified seller' => 'بائع موثّق',
            'Verify Your Email/Phone' => 'تحقق من بريدك الإلكتروني أو جوالك',
            'View All Products' => 'عرض كل المنتجات',
            'View All Sellers' => 'عرض كل البائعين',
            'View Order History' => 'عرض سجل الطلبات',
            'View Policy' => 'عرض السياسة',
            'Wallet' => 'المحفظة',
            'Wallet recharge history' => 'سجل شحن المحفظة',
            'warning' => 'تنبيه',
            'Warning: You cannot undo this action' => 'تنبيه: لا يمكن التراجع عن هذا الإجراء',
            'Warranty' => 'الضمان',
            'Welcome Coupon' => 'كوبون الترحيب',
            'Wholesale' => 'الجملة',
            'Write your question here...' => 'اكتب سؤالك هنا...',
            'You already have an account with this information. Please Login first.' => 'لديك حساب بهذه البيانات بالفعل. يرجى تسجيل الدخول أولاً.',
            'You have to add minimum ' => 'يجب إضافة حد أدنى ',
            'You must Login as customer to apply coupon' => 'يجب تسجيل الدخول كعميل لتطبيق الكوبون',
            'You need to put Transaction id' => 'يجب إدخال رقم العملية',
            'You order amount is less then the minimum order amount' => 'قيمة طلبك أقل من الحد الأدنى لقيمة الطلب',
            'Your order will be shipped for free' => 'سيتم شحن طلبك مجاناً',
            'Continue Shopping' => 'متابعة التسوق',
            'Nothing selected' => 'لم يتم اختيار شيء',
            'Nothing found' => 'لا توجد نتائج',
            'Browse' => 'تصفح',
            'File' => 'ملف',
            'Files' => 'ملفات',
        ];
    }

    public function up(): void
    {
        $now = now();

        foreach ($this->strings() as $english => $arabic) {
            $key = $this->key($english);

            if (DB::table('translations')->where('lang', 'en')->where('lang_key', $key)->doesntExist()) {
                DB::table('translations')->insert([
                    'lang' => 'en', 'lang_key' => $key, 'lang_value' => $english,
                    'created_at' => $now, 'updated_at' => $now,
                ]);
            }

            if (DB::table('translations')->where('lang', 'sa')->where('lang_key', $key)->doesntExist()) {
                DB::table('translations')->insert([
                    'lang' => 'sa', 'lang_key' => $key, 'lang_value' => $arabic,
                    'created_at' => $now, 'updated_at' => $now,
                ]);
            }
        }

        // translate() reads translations through the cache.
        cache()->forget('translations-sa');
        cache()->forget('translations-en');
    }

    public function down(): void
    {
        // Leave translations in place: admins may have edited them since.
    }
};
