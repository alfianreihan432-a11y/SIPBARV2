<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SiteSettingController extends Controller
{
    public function index()
    {
        $this->authorize('manage-site-settings');

        $settings = [
            'general' => SiteSetting::getByGroup('general'),
            'hero' => SiteSetting::getByGroup('hero'),
            'features' => SiteSetting::getByGroup('features'),
            'stats' => SiteSetting::getByGroup('stats'),
            'about' => SiteSetting::getByGroup('about'),
            'footer' => SiteSetting::getByGroup('footer'),
            'contact' => SiteSetting::getByGroup('contact'),
            'navigation' => SiteSetting::getByGroup('navigation'),
        ];

        $featureCards = SiteSetting::getJson('feature_cards', []);
        $statsData = SiteSetting::getJson('stats_data', []);
        $aboutFeatures = SiteSetting::getJson('about_features', []);
        $footerLinks = SiteSetting::getJson('footer_links', []);

        return view('pages.superadmin.landing-page', compact(
            'settings',
            'featureCards',
            'statsData',
            'aboutFeatures',
            'footerLinks'
        ));
    }

    public function updateGeneral(Request $request)
    {
        $this->authorize('manage-site-settings');

        $validator = Validator::make($request->all(), [
            'site_name'           => 'required|string|max:100',
            'site_subtitle'       => 'required|string|max:100',
            'site_title'          => 'required|string|max:200',
            'site_logo_landing'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'site_logo_login'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'site_logo_dashboard' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'site_favicon'        => 'nullable|image|mimes:ico,png,jpeg,jpg,svg|max:1024',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Custom validation for favicon aspect ratio (must be square)
        if ($request->hasFile('site_favicon')) {
            $favicon = $request->file('site_favicon');
            $imageInfo = getimagesize($favicon->getPathname());
            if ($imageInfo) {
                [$width, $height] = $imageInfo;
                if ($width !== $height) {
                    return back()->withErrors([
                        'site_favicon' => 'Logo favicon harus berbentuk persegi (contoh: 512x512px, 256x256px).'
                    ])->withInput();
                }
            }
        }

        // Logo Landing Page
        if ($request->hasFile('site_logo_landing')) {
            SiteSetting::deleteUploadedFile(SiteSetting::get('site_logo_landing'));
            $path = $request->file('site_logo_landing')->store('site-logos', 'public');
            SiteSetting::set('site_logo_landing', '/storage/'.$path, 'image', 'general');
        }

        // Logo Halaman Login
        if ($request->hasFile('site_logo_login')) {
            SiteSetting::deleteUploadedFile(SiteSetting::get('site_logo_login'));
            $path = $request->file('site_logo_login')->store('site-logos', 'public');
            SiteSetting::set('site_logo_login', '/storage/'.$path, 'image', 'general');
        }

        // Logo Dashboard (semua role)
        if ($request->hasFile('site_logo_dashboard')) {
            SiteSetting::deleteUploadedFile(SiteSetting::get('site_logo_dashboard'));
            $path = $request->file('site_logo_dashboard')->store('site-logos', 'public');
            SiteSetting::set('site_logo_dashboard', '/storage/'.$path, 'image', 'general');
        }

        // Favicon (Logo Tab Browser)
        if ($request->hasFile('site_favicon')) {
            SiteSetting::deleteUploadedFile(SiteSetting::get('site_favicon'));
            $path = $request->file('site_favicon')->store('favicons', 'public');
            SiteSetting::set('site_favicon', '/storage/'.$path, 'image', 'general');
        }

        SiteSetting::set('site_name', $request->site_name, 'text', 'general');
        SiteSetting::set('site_subtitle', $request->site_subtitle, 'text', 'general');
        SiteSetting::set('site_title', $request->site_title, 'text', 'general');

        return back()->with('success', 'Pengaturan umum berhasil diperbarui.');
    }

    public function updateHero(Request $request)
    {
        $this->authorize('manage-site-settings');

        $validator = Validator::make($request->all(), [
            'hero_badge' => 'required|string|max:80',
            'hero_title' => 'required|string|max:500',
            'hero_description' => 'required|string|max:1000',
            'hero_cta_text' => 'required|string|max:50',
            'hero_cta_alt_text' => 'required|string|max:50',
            'hero_background' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('hero_background')) {
            SiteSetting::deleteUploadedFile(SiteSetting::get('hero_background'));
            $bgPath = $request->file('hero_background')->store('hero-backgrounds', 'public');
            SiteSetting::set('hero_background', '/storage/'.$bgPath, 'image', 'hero');
        }

        SiteSetting::set('hero_badge', $request->hero_badge, 'text', 'hero');
        SiteSetting::set('hero_title', $request->hero_title, 'text', 'hero');
        SiteSetting::set('hero_description', $request->hero_description, 'text', 'hero');
        SiteSetting::set('hero_cta_text', $request->hero_cta_text, 'text', 'hero');
        SiteSetting::set('hero_cta_alt_text', $request->hero_cta_alt_text, 'text', 'hero');

        return back()->with('success', 'Section hero berhasil diperbarui.');
    }

    public function updateFeatures(Request $request)
    {
        $this->authorize('manage-site-settings');

        $validator = Validator::make($request->all(), [
            'features_eyebrow' => 'required|string|max:80',
            'features_title' => 'required|string|max:500',
            'features_description' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        SiteSetting::set('features_eyebrow', $request->features_eyebrow, 'text', 'features');
        SiteSetting::set('features_title', $request->features_title, 'text', 'features');
        SiteSetting::set('features_description', $request->features_description, 'text', 'features');

        return back()->with('success', 'Section fitur berhasil diperbarui.');
    }

    public function updateFeatureCards(Request $request)
    {
        $this->authorize('manage-site-settings');

        $featureCards = [];
        for ($i = 0; $i < 5; $i++) {
            $title = trim((string) $request->input("feature_title_{$i}"));
            if ($title === '') {
                continue;
            }

            $workflow = array_values(array_filter([
                trim((string) $request->input("feature_workflow_1_{$i}")),
                trim((string) $request->input("feature_workflow_2_{$i}")),
                trim((string) $request->input("feature_workflow_3_{$i}")),
            ]));

            $featureCards[] = [
                'index' => str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT),
                'title' => $title,
                'description' => $request->input("feature_description_{$i}"),
                'icon' => $request->input("feature_icon_{$i}"),
                'workflow' => $workflow,
                'link_text' => $request->input("feature_link_text_{$i}"),
                'route' => $request->input("feature_route_{$i}"),
            ];
        }

        SiteSetting::setJson('feature_cards', $featureCards, 'features');

        return back()->with('success', 'Kartu fitur berhasil diperbarui.');
    }

    public function updateStats(Request $request)
    {
        $this->authorize('manage-site-settings');

        $validator = Validator::make($request->all(), [
            'stats_eyebrow' => 'required|string|max:80',
            'stats_title' => 'required|string|max:500',
            'stats_description' => 'required|string|max:1000',
            'stats_cta_text' => 'required|string|max:80',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        SiteSetting::set('stats_eyebrow', $request->stats_eyebrow, 'text', 'stats');
        SiteSetting::set('stats_title', $request->stats_title, 'text', 'stats');
        SiteSetting::set('stats_description', $request->stats_description, 'text', 'stats');
        SiteSetting::set('stats_cta_text', $request->stats_cta_text, 'text', 'stats');

        return back()->with('success', 'Section statistik berhasil diperbarui.');
    }

    public function updateStatsData(Request $request)
    {
        $this->authorize('manage-site-settings');

        $statsData = [];
        for ($i = 0; $i < 4; $i++) {
            $label = trim((string) $request->input("stat_label_{$i}"));
            if ($label === '') {
                continue;
            }

            $statsData[] = [
                'label' => $label,
                'sublabel' => $request->input("stat_sublabel_{$i}"),
                'trend' => $request->input("stat_trend_{$i}"),
                'icon' => $request->input("stat_icon_{$i}"),
                'color' => $request->input("stat_color_{$i}"),
            ];
        }

        SiteSetting::setJson('stats_data', $statsData, 'stats');

        return back()->with('success', 'Label statistik berhasil diperbarui.');
    }

    public function updateAbout(Request $request)
    {
        $this->authorize('manage-site-settings');

        $validator = Validator::make($request->all(), [
            'about_eyebrow' => 'required|string|max:80',
            'about_title' => 'required|string|max:500',
            'about_description' => 'required|string|max:1000',
            'about_badge_year' => 'required|string|max:10',
            'about_badge_name' => 'required|string|max:100',
            'about_caption_label' => 'required|string|max:80',
            'about_caption_sub' => 'required|string|max:300',
            'about_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('about_image')) {
            SiteSetting::deleteUploadedFile(SiteSetting::get('about_image'));
            $imgPath = $request->file('about_image')->store('about-images', 'public');
            SiteSetting::set('about_image', '/storage/'.$imgPath, 'image', 'about');
        }

        SiteSetting::set('about_eyebrow', $request->about_eyebrow, 'text', 'about');
        SiteSetting::set('about_title', $request->about_title, 'text', 'about');
        SiteSetting::set('about_description', $request->about_description, 'text', 'about');
        SiteSetting::set('about_badge_year', $request->about_badge_year, 'text', 'about');
        SiteSetting::set('about_badge_name', $request->about_badge_name, 'text', 'about');
        SiteSetting::set('about_caption_label', $request->about_caption_label, 'text', 'about');
        SiteSetting::set('about_caption_sub', $request->about_caption_sub, 'text', 'about');

        return back()->with('success', 'Section tentang berhasil diperbarui.');
    }

    public function updateAboutFeatures(Request $request)
    {
        $this->authorize('manage-site-settings');

        $aboutFeatures = [];
        for ($i = 0; $i < 4; $i++) {
            $title = trim((string) $request->input("about_feature_title_{$i}"));
            if ($title === '') {
                continue;
            }

            $aboutFeatures[] = [
                'number' => str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT),
                'title' => $title,
                'description' => $request->input("about_feature_description_{$i}"),
                'icon' => $request->input("about_feature_icon_{$i}"),
                'primary' => $request->input("about_feature_primary_{$i}") === '1',
            ];
        }

        SiteSetting::setJson('about_features', $aboutFeatures, 'about');

        return back()->with('success', 'Fitur tentang berhasil diperbarui.');
    }

    public function updateFooter(Request $request)
    {
        $this->authorize('manage-site-settings');

        $validator = Validator::make($request->all(), [
            'footer_brand_name' => 'required|string|max:50',
            'footer_brand_subtitle' => 'required|string|max:50',
            'footer_description' => 'required|string|max:300',
            'footer_copyright' => 'required|string|max:200',
            'footer_heading_menu' => 'required|string|max:50',
            'footer_heading_features' => 'required|string|max:50',
            'footer_heading_help' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        SiteSetting::set('footer_brand_name', $request->footer_brand_name, 'text', 'footer');
        SiteSetting::set('footer_brand_subtitle', $request->footer_brand_subtitle, 'text', 'footer');
        SiteSetting::set('footer_description', $request->footer_description, 'text', 'footer');
        SiteSetting::set('footer_copyright', $request->footer_copyright, 'text', 'footer');
        SiteSetting::set('footer_heading_menu', $request->footer_heading_menu, 'text', 'footer');
        SiteSetting::set('footer_heading_features', $request->footer_heading_features, 'text', 'footer');
        SiteSetting::set('footer_heading_help', $request->footer_heading_help, 'text', 'footer');

        return back()->with('success', 'Footer berhasil diperbarui.');
    }

    public function updateFooterLinks(Request $request)
    {
        $this->authorize('manage-site-settings');

        $footerLinks = [
            'navigation' => [],
            'information' => [],
            'legal' => [],
            'social' => [],
        ];

        for ($i = 0; $i < 4; $i++) {
            $text = trim((string) $request->input("nav_link_text_{$i}"));
            if ($text !== '') {
                $footerLinks['navigation'][] = [
                    'text' => $text,
                    'url' => $request->input("nav_link_url_{$i}"),
                ];
            }
        }

        for ($i = 0; $i < 5; $i++) {
            $text = trim((string) $request->input("info_link_text_{$i}"));
            if ($text !== '') {
                $footerLinks['information'][] = [
                    'text' => $text,
                    'url' => $request->input("info_link_url_{$i}"),
                ];
            }
        }

        for ($i = 0; $i < 4; $i++) {
            $text = trim((string) $request->input("legal_link_text_{$i}"));
            if ($text !== '') {
                $footerLinks['legal'][] = [
                    'text' => $text,
                    'url' => $request->input("legal_link_url_{$i}"),
                ];
            }
        }

        for ($i = 0; $i < 3; $i++) {
            $text = trim((string) $request->input("social_link_text_{$i}"));
            if ($text !== '') {
                $footerLinks['social'][] = [
                    'text' => $text,
                    'url' => $request->input("social_link_url_{$i}"),
                    'icon' => $request->input("social_link_icon_{$i}"),
                ];
            }
        }

        SiteSetting::setJson('footer_links', $footerLinks, 'footer');

        return back()->with('success', 'Link footer berhasil diperbarui.');
    }

    public function updateContact(Request $request)
    {
        $this->authorize('manage-site-settings');

        $validator = Validator::make($request->all(), [
            'contact_email' => 'required|email|max:100',
            'contact_phone' => 'required|string|max:50',
            'contact_address' => 'required|string|max:200',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        SiteSetting::set('contact_email', $request->contact_email, 'text', 'contact');
        SiteSetting::set('contact_phone', $request->contact_phone, 'text', 'contact');
        SiteSetting::set('contact_address', $request->contact_address, 'text', 'contact');

        return back()->with('success', 'Informasi kontak berhasil diperbarui.');
    }

    public function updateNavigation(Request $request)
    {
        $this->authorize('manage-site-settings');

        $validator = Validator::make($request->all(), [
            'nav_link_home' => 'required|string|max:50',
            'nav_link_features' => 'required|string|max:50',
            'nav_link_inventory' => 'required|string|max:50',
            'nav_link_about' => 'required|string|max:50',
            'nav_link_help' => 'required|string|max:50',
            'nav_link_features_mobile' => 'required|string|max:50',
            'nav_link_inventory_mobile' => 'required|string|max:50',
            'nav_link_about_mobile' => 'required|string|max:50',
            'nav_link_help_mobile' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        SiteSetting::set('nav_link_home', $request->nav_link_home, 'text', 'navigation');
        SiteSetting::set('nav_link_features', $request->nav_link_features, 'text', 'navigation');
        SiteSetting::set('nav_link_inventory', $request->nav_link_inventory, 'text', 'navigation');
        SiteSetting::set('nav_link_about', $request->nav_link_about, 'text', 'navigation');
        SiteSetting::set('nav_link_help', $request->nav_link_help, 'text', 'navigation');
        SiteSetting::set('nav_link_features_mobile', $request->nav_link_features_mobile, 'text', 'navigation');
        SiteSetting::set('nav_link_inventory_mobile', $request->nav_link_inventory_mobile, 'text', 'navigation');
        SiteSetting::set('nav_link_about_mobile', $request->nav_link_about_mobile, 'text', 'navigation');
        SiteSetting::set('nav_link_help_mobile', $request->nav_link_help_mobile, 'text', 'navigation');

        return back()->with('success', 'Navigasi berhasil diperbarui.');
    }
}
