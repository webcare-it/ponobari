<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Banner;
use App\Models\GeneralSetting;
use App\Models\GoogleFacebookCode;
use App\Models\PaymentPolicy;
use App\Models\PrivacyPolicy;
use App\Models\RefundPolicy;
use App\Models\TermsCondition;
use Codeboxr\PathaoCourier\Facade\PathaoCourier;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    public function bannerAdd()
    {
        return view('admin.settings.banner-create');
    }

    public function bannerList()
    {
        $banners = Banner::orderBy('created_at', 'desc')->get();
        return view('admin.settings.banner-list', compact('banners'));
    }

    public function bannerStore(Request $request)
    {
        $this->validate($request, [
            'type' => 'required',
            'image' => 'required',
        ]);
        $bannerImage = $request->file('image');
        $imageName = time().'.'.$bannerImage->getClientOriginalExtension();
        $destinationPath = 'setting';
        $imgFile = Image::make($bannerImage->getRealPath());
        $imgFile->save($destinationPath.'/'.$imageName);
        $bannerImage->move($destinationPath, $imageName);

        $addNewBanner = new Banner();
        $addNewBanner->type = $request->type;
        $addNewBanner->image = $imageName;
        $addNewBanner->save();
        return redirect('/banner/list')->with('success', 'Banner has been created.');
    }

    public function bannerEdit($id)
    {
        $banner = Banner::find($id);
        return view('admin.settings.banner-edit', compact('banner'));
    }

    public function bannerUpdate(Request $request, $id)
    {
        $this->validate($request, [
            'type' => 'required',
            'image' => 'required',
        ]);
        $banner = Banner::find($id);
        if ($request->hasFile('image')){
            if ($banner->image && file_exists(public_path('/setting/'.$banner->image))){
                unlink(public_path('/setting/'.$banner->image));
            }

            $update_bannerImage = $request->file('image');
            $update_imageName = time().'.'.$update_bannerImage->getClientOriginalExtension();
            $update_destinationPath = 'setting';
            $update_imgFile = Image::make($update_bannerImage->getRealPath());
            $update_imgFile->save($update_destinationPath.'/'.$update_imageName);
            $update_bannerImage->move($update_destinationPath, $update_imageName);
            $banner->image = $update_imageName;
        }
        $banner->type = $request->type;
        $banner->save();
        return redirect('/banner/list')->with('success', 'Banner has been created.');
    }

    public function bannerDelete($id)
    {
        $banner = Banner::find($id);
        $banner->delete();
        File::delete(public_path('setting/'.$banner->image));
        return redirect()->back()->with('success', 'Banner has been deleted');
    }


    public function privacyPolicy()
    {
        $privacyPolicy = PrivacyPolicy::first();
        return view('admin.settings.privacy-policy', compact('privacyPolicy'));
    }

    public function privacyPolicyStore(Request $request)
    {
        PrivacyPolicy::updateOrCreate([
            'id' => 1
        ], [
            'privacy_policy' => $request->privacy_policy
        ]);
        $this->setSuccessMessage('Privacy Policy has been updated');
        return redirect()->back();
    }

    public function termsCondition()
    {
        $termsCondition = TermsCondition::first();
        return view('admin.settings.terms-condition', compact('termsCondition'));
    }

    public function termsConditionStore(Request $request)
    {
        TermsCondition::updateOrCreate([
            'id' => 1
        ], [
            'terms_condition' => $request->terms_condition
        ]);
        $this->setSuccessMessage('Terms Condition has been updated');
        return redirect()->back();
    }

    public function refundPolicy()
    {
        $refundPolicy = RefundPolicy::first();
        return view('admin.settings.refund-policy', compact('refundPolicy'));
    }

    public function refundPolicyStore(Request $request)
    {
        RefundPolicy::updateOrCreate([
            'id' => 1
        ], [
            'refund_policy' => $request->refund_policy
        ]);
        $this->setSuccessMessage('Terms Condition has been updated');
        return redirect()->back();
    }

    public function paymentPolicy()
    {
        $paymentPolicy = PaymentPolicy::first();
        return view('admin.settings.payment-policy', compact('paymentPolicy'));
    }

    public function paymentPolicyStore(Request $request)
    {
        PaymentPolicy::updateOrCreate([
            'id' => 1
        ], [
            'payment_policy' => $request->payment_policy
        ]);
        $this->setSuccessMessage('Payment policy has been updated');
        return redirect()->back();
    }

    public function adminAbout()
    {
        $about = About::first();
        return view('admin.settings.about', compact('about'));
    }

    public function adminAboutStore(Request $request)
    {
        About::updateOrCreate([
            'id' => 1
        ], [
            'about' => $request->about
        ]);
        $this->setSuccessMessage('About has been updated');
        return redirect()->back();
    }

    public function showPathaoCourier(){
        $general_setting = GeneralSetting::first();
        return view('admin.settings.pathao-courier', compact('general_setting'));
    }

    public function updatePathaoCourier(Request $request)
    {
        $general_setting = GeneralSetting::first();
        $general_setting->pathao_client_id = $request->pathao_client_id;
        $general_setting->pathao_client_secret = $request->pathao_client_secret;
        $general_setting->pathao_username = $request->pathao_username;
        $general_setting->pathao_password = $request->pathao_password;
        $general_setting->pathao_sandbox = $request->has('pathao_sandbox') ? true : false;
        $general_setting->save();

        return redirect()->back()->withSuccess('Pathao settings updated successfully!');
    }

    public function pathaoCourierStore(Request $request)
    {
       PathaoCourier::order()->create([
            "store_id"            => $request->store_id, // Find in store list,
            "merchant_order_id"   => $request->merchant_order_id, // Unique order id
            "recipient_name"      => $request->recipient_name, // Customer name
            "recipient_phone"     => $request->recipient_phone, // Customer phone
            "recipient_address"   => $request->recipient_address, // Customer address
            "recipient_city"      => $request->recipient_city, // Find in city method
            "recipient_zone"      => $request->recipient_zone, // Find in zone method
            "recipient_area"      => $request->recipient_area, // Find in Area method
            "delivery_type"       => $request->delivery_type, // 48 for normal delivery or 12 for on demand delivery
            "item_type"           => $request->item_type, // 1 for document,
            "special_instruction" => $request->speciali_instruction,
            "item_quantity"       => $request->item_quantity, // item quantity
            "item_weight"         => $request->item_weight, // parcel weight
            "amount_to_collect"   => $request->amount_to_collect, // amount to collect
            "item_description"    => $request->item_description // product details
        ]);

        return redirect()->back()->with('success', 'Parcel has been created');
    }


    public function gtmForm()
    {
        $code = GoogleFacebookCode::find(1);
        return view('admin.settings.gtm', compact('code'));
    }

    public function gtmStore(Request $request)
    {
        $this->validate($request, [
            'gtm_id' => 'required',
        ]);

        GoogleFacebookCode::updateOrCreate([
            'id' => 1
        ],[
            'gtm_id' => $request->gtm_id,
        ]);

        return redirect()->back()->with('success', 'Code has been updtaed');
    }

    public function generalSetting ()
    {
        $general_setting = GeneralSetting::first();
        return view('admin.general_setting.index', compact('general_setting'));
    }

    // Add new method for SMTP settings
    public function smtpSettings()
    {
        $general_setting = GeneralSetting::first();
        return view('admin.settings.smtp-settings', compact('general_setting'));
    }

    // Add new method to update SMTP settings
    public function updateSmtpSettings(Request $request)
    {
        $general_setting = GeneralSetting::first();
        
        // Update admin notification email
        $general_setting->admin_notification_email = $request->admin_notification_email;
        
        // Update SMTP settings
        $general_setting->mail_host = $request->mail_host;
        $general_setting->mail_port = $request->mail_port;
        $general_setting->mail_username = $request->mail_username;
        $general_setting->mail_password = $request->mail_password;
        $general_setting->mail_encryption = $request->mail_encryption;
        $general_setting->mail_from_address = $request->mail_from_address;
        $general_setting->mail_from_name = $request->mail_from_name;
        
        $general_setting->save();

        // Update the .env file with new SMTP settings
        $this->updateEnvFile($request);

        // Clear config cache to apply new mail settings
        Artisan::call('config:clear');

        return redirect()->back()->with('success', 'SMTP settings updated successfully!');
    }

    // Helper method to update .env file
    private function updateEnvFile($request)
    {
        $envFilePath = base_path('.env');
        $envContent = file_get_contents($envFilePath);

        // Update mail settings in .env file
        $envContent = preg_replace('/MAIL_HOST=.*/', 'MAIL_HOST=' . ($request->mail_host ?? 'mailpit'), $envContent);
        $envContent = preg_replace('/MAIL_PORT=.*/', 'MAIL_PORT=' . ($request->mail_port ?? '1025'), $envContent);
        $envContent = preg_replace('/MAIL_USERNAME=.*/', 'MAIL_USERNAME=' . ($request->mail_username ?? 'null'), $envContent);
        $envContent = preg_replace('/MAIL_PASSWORD=.*/', 'MAIL_PASSWORD=' . ($request->mail_password ?? 'null'), $envContent);
        $envContent = preg_replace('/MAIL_ENCRYPTION=.*/', 'MAIL_ENCRYPTION=' . ($request->mail_encryption ?? 'null'), $envContent);
        $envContent = preg_replace('/MAIL_FROM_ADDRESS=.*/', 'MAIL_FROM_ADDRESS="' . ($request->mail_from_address ?? 'hello@example.com') . '"', $envContent);
        $envContent = preg_replace('/MAIL_FROM_NAME=.*/', 'MAIL_FROM_NAME="' . ($request->mail_from_name ?? '${APP_NAME}') . '"', $envContent);

        file_put_contents($envFilePath, $envContent);
    }

    public function showSteadfastCourier()
    {
        $general_setting = GeneralSetting::first();
        return view('admin.settings.steadfast-courier', compact('general_setting'));
    }

    public function updateSteadfastCourier(Request $request)
    {
        $general_setting = GeneralSetting::first();
        $general_setting->steadfast_api_key = $request->steadfast_api_key;
        $general_setting->steadfast_secret_key = $request->steadfast_secret_key;
        $general_setting->save();

        return redirect()->back()->withSuccess('Steadfast settings updated successfully!');
    }

    public function updateGeneralSetting (Request $request)
    {
        $general_setting = GeneralSetting::first();
        if($request->hasFile('logo')){
            if(file_exists(public_path('setting/'.$general_setting->logo))){
                File::delete(public_path('setting/'.$general_setting->logo));
                $name = time() . '.' . $request->logo->getClientOriginalExtension();
                $request->logo->move('setting/', $name);
                $general_setting->logo = $name;
            }
            else{
                $name = time() . '.' . $request->logo->getClientOriginalExtension();
                $request->logo->move('setting/', $name);
                $general_setting->logo = $name;
            }

        }

        $general_setting->phone = $request->phone;
        $general_setting->whatsapp = $request->whatsapp;
        $general_setting->email = $request->email;
        $general_setting->facebook = $request->facebook;
        $general_setting->instagram = $request->instagram;
        $general_setting->twitter = $request->twitter;
        $general_setting->youtube = $request->youtube;
        $general_setting->address = $request->address;

        $general_setting->save();

        return redirect()->back()->withSuccess('Updated Successfully!!');
    }
    
    // Droploo API Credentials
    public function droplooApiCredentials()
    {
        $general_setting = GeneralSetting::first();
        return view('admin.settings.droploo-api', compact('general_setting'));
    }
    
    public function updateDroplooApiCredentials(Request $request)
    {
        $this->validate($request, [
            'droploo_app_key' => 'required',
            'droploo_app_secret' => 'required',
            'droploo_username' => 'required',
        ]);
        
        $general_setting = GeneralSetting::first();
        $general_setting->droploo_app_key = $request->droploo_app_key;
        $general_setting->droploo_app_secret = $request->droploo_app_secret;
        $general_setting->droploo_username = $request->droploo_username;
        $general_setting->save();
        
        return redirect()->back()->with('success', 'Droploo API credentials updated successfully!');
    }
    
    // Website Theme Settings
    public function websiteTheme()
    {
        $general_setting = GeneralSetting::first();
        return view('admin.settings.website-theme', compact('general_setting'));
    }
    
    public function updateWebsiteTheme(Request $request)
    {
        $general_setting = GeneralSetting::first();
        $general_setting->website_primary_color = $request->website_primary_color;
        $general_setting->website_secondary_color = $request->website_secondary_color;
        $general_setting->save();
        
        return redirect()->back()->with('success', 'Website theme updated successfully!');
    }
    
    // Theme Colors Management
    public function themeColors()
    {
        $general_setting = GeneralSetting::first();
        return view('admin.settings.theme-colors', compact('general_setting'));
    }
    
    public function updateThemeColors(Request $request)
    {
        $this->validate($request, [
            'primary_color' => 'required|regex:/^#[0-9A-F]{6}$/i',
            'secondary_color' => 'required|regex:/^#[0-9A-F]{6}$/i',
            'accent_color' => 'required|regex:/^#[0-9A-F]{6}$/i',
            'category_bg_color' => 'required|regex:/^#[0-9A-F]{6}$/i',
            'header_bg_color' => 'required|regex:/^#[0-9A-F]{6}$/i',
            'footer_bg_color' => 'required|regex:/^#[0-9A-F]{6}$/i',
        ], [
            'primary_color.regex' => 'Primary color must be a valid hex color (e.g., #053C6B)',
            'secondary_color.regex' => 'Secondary color must be a valid hex color (e.g., #333333)',
            'accent_color.regex' => 'Accent color must be a valid hex color (e.g., #f41127)',
            'category_bg_color.regex' => 'Category background color must be a valid hex color',
            'header_bg_color.regex' => 'Header background color must be a valid hex color',
            'footer_bg_color.regex' => 'Footer background color must be a valid hex color',
        ]);
        
        $general_setting = GeneralSetting::first();
        $general_setting->primary_color = $request->primary_color;
        $general_setting->secondary_color = $request->secondary_color;
        $general_setting->accent_color = $request->accent_color;
        $general_setting->category_bg_color = $request->category_bg_color;
        $general_setting->header_bg_color = $request->header_bg_color;
        $general_setting->footer_bg_color = $request->footer_bg_color;
        $general_setting->save();
        
        return redirect()->back()->with('success', 'Theme colors updated successfully!');
    }
}