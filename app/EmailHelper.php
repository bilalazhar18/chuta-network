<?php
/**
 * Class EmailHelper
 *
 * @category Worketic
 *
 * @package Worketic
 * @author  Amentotech <theamentotech@gmail.com>
 * @license http://www.amentotech.com Amentotech
 * @link    http://www.amentotech.com
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EmailHelper
 *
 */
class EmailHelper extends Model
{
    /**
     * Get email header
     *
     * @access public
     *
     * @return mixed
     */
    public static function getEmailHeader()
    {
        ob_start();
        $setting = SiteManagement::getMetaValue('email_data');
        $email_banner = url('C:/xampp/htdocs/bitbucketRepo/chuta-network/public/uploads/settings/email/1555333800-logo.png');
        ?>
        <div style="min-width:100%;background-color:#f6f7f9;margin:0;width:100%;color:#283951;font-family:'Helvetica','Arial',sans-serif;padding: 60px 0;">
        <div style="background: #FFF;max-width: 600px; width: 100%; margin: 0 auto; overflow: hidden; color: #919191; font:400 16px/26px 'Open Sans', Arial, Helvetica, sans-serif;">
            <div style="width: 100%; float: left; padding: 30px 0; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">
                <strong style="float: left; padding: 0 0 0 30px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">
                <a style="float: left; color: #55acee; text-decoration: none;" href="#">Chuta Network</a>
            </strong>
            </div>
            <div id="tg-banner" class="tg-banner" style="width: 100%; float: left; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">
                <img style="width: 100%; height: auto; display: block;" src="https://chuta.network/dev/public/uploads/settings/general/1555333800-logo.png" alt="Chuta Network">
		    </div>
		<div style="width: 100%; float: left; padding: 30px 30px 0; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">
			<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">
                <div style="width: 100%; float: left;">
        <?php
        return ob_get_clean();
    }

    /**
     * Get email footer
     *
     * @access public
     *
     * @return mixed
     */
    public static function getEmailFooter()
    {
        ob_start();
        $setting = SiteManagement::getMetaValue('footer_settings');
        $copyright = !empty($setting) && !empty($setting['copyright']) ? $setting['copyright'] : 'Copyright Worketic All Rights Reserved';
        ?>
        </div>
        </div>
        </div>
            <div style="width:100%;float:left;background: #002c49;padding: 30px 15px;text-align:center;box-sizing:border-box;border-radius: 0  0 5px 5px;">
                <p style="font-size: 13px; line-height: 13px; color: #aaaaaa; margin: 0; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">
                Copyright © 2019, All Right Reserved <a href="<?php url('/'); ?>" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; color: #348eda; margin: 0; padding: 0;">Chuta Network</a></p>
            </div>
        </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Get site title
     *
     * @access public
     *
     * @return mixed
     */
    public static function getSiteTitle()
    {
        $settings = SiteManagement::getMetaValue('settings');
        $title = !empty($settings) && !empty($settings[0]['title']) ? $settings[0]['title'] : 'Worketic';
        return $title;
    }

    /**
     * Get email from name
     *
     * @access public
     *
     * @return mixed
     */
    public static function getEmailFrom()
    {
        $setting = SiteManagement::getMetaValue('email_data');
        $email = !empty($setting) && !empty($setting[0]['from_email']) ? $setting[0]['from_email'] : 'info@amentotech.com';
        return $email;
    }

    /**
     * Get email id
     *
     * @access public
     *
     * @return mixed
     */
    public static function getEmailID()
    {
        $setting = SiteManagement::getMetaValue('email_data');
        $email_id = !empty($setting) && !empty($setting[0]['from_email_id']) ? $setting[0]['from_email_id'] : 'info@amentotech.com';
        return $email_id;
    }

    /**
     * Get site logo
     *
     * @access public
     *
     * @return mixed
     */
    public static function getSiteLogo()
    {
        $email_data = !empty(SiteManagement::getMetaValue('email_data')) ? SiteManagement::getMetaValue('email_data') : array();
        if (!empty($email_data[0]['email_logo'])) {
            $logo = $email_data[0]['email_logo'];    
            return !empty($logo) ? url('uploads/settings/email/'.$logo) : url('images/logo.png');
        } else {
            $setting = SiteManagement::getMetaValue('settings');
            $logo = !empty($setting) && !empty($setting[0]['logo']) ? $setting[0]['logo'] : '';
            return !empty($logo) ? url('uploads/settings/general/'.$logo) : url('images/logo.png');
        }
    }

    /**
     * Get email signature
     *
     * @access public
     *
     * @return mixed
     */
    public static function getSignature()
    {
        ob_start();
        $setting = SiteManagement::getMetaValue('email_data');
        $sender_name = "Chuta Network";
        $sender_tagline = "Hire expert students for any job, Online";
       // $sender_url = "https://chuta.network";
        $sender_url = "https://chuta.network/dev/public/";
        ?>
        <div style="width: 100%; float: left; padding: 15px 0 0; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">
            <div style="float: left; border-radius: 5px; overflow: hidden; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">
                <img style="display: block;" height=50 width=90 src="https://chuta.network/dev/public/uploads/settings/general/1555333800-logo.png" alt="Chuta Network">
            </div>
            <div style="overflow: hidden; padding: 0 0 0 20px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">
                <p style="margin: 0 0 7px; font-size: 14px; line-height: 14px; color: #919191; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">Regards</p>
                <h2 style="font-size: 18px; line-height: 18px; margin: 0 0 5px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; color: #333; font-weight: normal;font-family: 'Work Sans', Arial, Helvetica, sans-serif;"><?php echo $sender_name; ?></h2>
                <p style="margin: 0 0 7px; font-size: 14px; line-height: 14px; color: #919191; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;"><?php echo $sender_tagline; ?></p>
                <p style="margin: 0; font-size: 14px; line-height: 14px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;"><a style=" color: #55acee; text-decoration: none;" href="<?php echo $sender_url; ?>"><?php echo $sender_url; ?></a></p>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

}