<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CMS\Page;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CMSController extends Controller
{
    public function home()
    {
        $page = Page::firstOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Motor Bazar - Premium Auctions',
                'hero_image' => '/images/cars/mclaren.png',
                'is_published' => true,
                'content' => [
                    'hero' => [
                        'title' => "Find your\ndream car",
                        'subtitle' => 'Access the world\'s most exclusive automotive marketplace.',
                        'announcement' => 'Professional Marketplace',
                        'primary_cta_label' => 'Browse Inventory',
                        'primary_cta_url' => '/auctions',
                        'secondary_cta_label' => 'Learn About Us',
                        'secondary_cta_url' => '#',
                        'background_mode' => 'blend',
                        'background_color' => '#0e1017',
                        'background_overlay_opacity' => 0.72,
                        'background_overlay_direction' => 'horizontal',
                        'background_overlay_enabled' => true,
                        'background_image' => '/images/hero-bg.png',
                        'car_scale' => 1,
                    ],
                    'body_types' => [
                        ['label' => 'Sedan', 'icon' => 'car', 'slug' => 'sedan'],
                        ['label' => 'SUV', 'icon' => 'shield', 'slug' => 'suv'],
                        ['label' => 'Coupe', 'icon' => 'zap', 'slug' => 'coupe'],
                        ['label' => 'Hatch', 'icon' => 'box', 'slug' => 'hatchback'],
                        ['label' => 'Cabrio', 'icon' => 'sun', 'slug' => 'cabrio'],
                        ['label' => 'Pickup', 'icon' => 'truck', 'slug' => 'pickup'],
                    ],
                    'brands' => [
                        ['name' => 'Mercedes-Benz', 'slug' => 'mercedes-benz'],
                        ['name' => 'BMW', 'slug' => 'bmw'],
                        ['name' => 'Audi', 'slug' => 'audi'],
                        ['name' => 'Porsche', 'slug' => 'porsche'],
                    ],
                    'lead_form_brands' => [
                        ['name' => 'Mercedes-Benz', 'slug' => 'mercedes-benz'],
                        ['name' => 'BMW', 'slug' => 'bmw'],
                    ],
                    'navbar' => [
                        'phone' => '+1 (234) 567 890',
                        'hours' => 'Mon - Fri: 9:00 - 18:00',
                        'sticky' => true,
                        'glass' => true,
                    ]
                ]
            ]
        );

        $recentCars = \App\Models\Car::with('brand')->latest()->limit(12)->get();
        $brands = Brand::orderBy('name')->get();
        
        // Check if this is the new layout route
        if (request()->routeIs('admin.cms.home.fixed')) {
            return view('admin.cms.home_fixed', compact('page', 'recentCars'));
        }
        
        return view('admin.cms.home', compact('page', 'recentCars', 'brands'));
    }

    public function clearCache()
    {
        Cache::forget('homepage.cms.page');
        Cache::forget('homepage.featured.auctions');
        Cache::forget('homepage.stats');
        
        return response()->json(['success' => true]);
    }

    public function updateHome(Request $request)
    {
        $page = Page::where('slug', 'home')->firstOrFail();
        $content = $page->content ?? [];
        
        $request->validate([
            'title' => 'required|string|max:255',
            'hero_title' => 'required|string',
            'hero_subtitle' => 'nullable|string',
            'hero_announcement' => 'nullable|string',
            'hero_image' => 'nullable|string',
            'hero_image_upload' => 'nullable|file|image|max:4096',
            'hero_image_choice' => 'nullable|string',
            'hero_background_image' => 'nullable|string',
            'hero_background_color' => 'nullable|string|max:30',
            'hero_background_color_secondary' => 'nullable|string|max:30',
            'hero_background_gradient_angle' => 'nullable|numeric',
            'hero_background_mode' => 'nullable|string|in:solid,gradient,image,custom',
            'hero_custom_css' => 'nullable|string',
            'hero_background_opacity' => 'nullable|numeric|between:0,1',
            'hero_background_direction' => 'nullable|in:horizontal,vertical',
            'hero_background_overlay_enabled' => 'nullable|boolean',
            'hero_car_scale' => 'nullable|numeric',
            'primary_cta_label' => 'nullable|string',
            'primary_cta_url' => 'nullable|string',
            'secondary_cta_label' => 'nullable|string',
            'secondary_cta_url' => 'nullable|string',
            'body_types' => 'nullable|array',
            'brands' => 'nullable|array',
            'lead_form_brands' => 'nullable|array',
            'lead_form' => 'nullable|array',
            'lead_form.step1' => 'nullable|array',
            'lead_form.step2' => 'nullable|array',
            'lead_form.step3' => 'nullable|array',
            'footer_background_color' => 'nullable|string|max:30',
            'navbar_phone' => 'nullable|string|max:50',
            'navbar_hours' => 'nullable|string|max:100',
            'navbar_sticky' => 'nullable|boolean',
            'navbar_glass' => 'nullable|boolean',
            'navbar.bg_color' => 'nullable|string|max:20',
            'navbar.text_color' => 'nullable|string|max:20',
        ]);

        $heroBackgroundImage = data_get($content, 'hero.background_image', '/images/hero-bg.png');
        $heroCarImage = $page->hero_image ?: '/images/cars/mclaren.png';

        if ($request->hasFile('hero_background_upload')) {
            $heroBackgroundImage = Storage::disk('public')->putFile('hero-backgrounds', $request->file('hero_background_upload'));
            $heroBackgroundImage = Storage::url($heroBackgroundImage);
        } elseif ($request->filled('hero_background_image')) {
            $heroBackgroundImage = $request->input('hero_background_image');
        }

        if ($request->hasFile('hero_image_upload')) {
            $heroCarImage = Storage::disk('public')->putFile('hero-assets', $request->file('hero_image_upload'));
            $heroCarImage = Storage::url($heroCarImage);
        } elseif ($request->filled('hero_image_choice')) {
            $heroCarImage = $request->input('hero_image_choice');
        } elseif ($request->filled('hero_image')) {
            $heroCarImage = $request->input('hero_image');
        }

        $heroBackgroundColor = $request->input('hero_background_color') ?? data_get($content, 'hero.background_color', '#0e1017');
        $heroBackgroundOpacity = (float) ($request->input('hero_background_opacity') ?? data_get($content, 'hero.background_overlay_opacity', 0.72));

        $content['hero'] = [
            'title' => $this->sanitizeHeroHtml($request->input('hero_title')),
            'subtitle' => $this->sanitizeHeroHtml($request->input('hero_subtitle')),
            'announcement' => $this->sanitizeHeroHtml($request->input('hero_announcement')),
            'primary_cta_label' => $request->input('primary_cta_label'),
            'primary_cta_url' => $request->input('primary_cta_url'),
            'secondary_cta_label' => $request->input('secondary_cta_label'),
            'secondary_cta_url' => $request->input('secondary_cta_url'),
            'background_mode' => $request->input('hero_background_mode') ?? data_get($content, 'hero.background_mode', 'image'),
            'background_color' => $heroBackgroundColor,
            'background_color_secondary' => $request->input('hero_background_color_secondary') ?? data_get($content, 'hero.background_color_secondary', '#1a1d26'),
            'background_gradient_angle' => $request->input('hero_background_gradient_angle') ?? data_get($content, 'hero.background_gradient_angle', 135),
            'custom_css' => $request->input('hero_custom_css') ?? data_get($content, 'hero.custom_css', ''),
            'background_overlay_opacity' => $heroBackgroundOpacity,
            'background_overlay_direction' => $request->input('hero_background_direction') ?? data_get($content, 'hero.background_overlay_direction', 'horizontal'),
            'background_overlay_enabled' => $request->boolean('hero_background_overlay_enabled', data_get($content, 'hero.background_overlay_enabled', true)),
            'background_image' => $heroBackgroundImage,
            'car_scale' => (float) ($request->input('hero_car_scale') ?? data_get($content, 'hero.car_scale', 1)),
        ];

        $page->hero_image = $heroCarImage;
        $page->title = $request->input('title');
        
        
        if ($request->has('body_types')) {
            $content['body_types'] = $request->input('body_types');
        }
        
        if ($request->has('brands')) {
            $content['brands'] = $request->input('brands');
        }

        if ($request->has('lead_form_brands')) {
            $content['lead_form_brands'] = $request->input('lead_form_brands');
        }

        // Save Lead Form text configuration (step labels, headings, etc.)
        if ($request->has('lead_form')) {
            $leadFormInput = $request->input('lead_form', []);
            $content['lead_form'] = [
                'wizard_w1'    => data_get($leadFormInput, 'wizard_w1', data_get($content, 'lead_form.wizard_w1', 'Select')),
                'wizard_w2'    => data_get($leadFormInput, 'wizard_w2', data_get($content, 'lead_form.wizard_w2', 'Customize')),
                'wizard_w3'    => data_get($leadFormInput, 'wizard_w3', data_get($content, 'lead_form.wizard_w3', 'Submit')),
                'step1' => [
                    'title'        => data_get($leadFormInput, 'step1.title', 'Choose brand, model, and year'),
                    'subtitle'     => data_get($leadFormInput, 'step1.subtitle', 'Pick a brand first. The model list updates automatically.'),
                    'brand_label'  => data_get($leadFormInput, 'step1.brand_label', 'Brand Selection'),
                    'model_label'  => data_get($leadFormInput, 'step1.model_label', 'Model'),
                    'year_label'   => data_get($leadFormInput, 'step1.year_label', 'Year'),
                    'button_label' => data_get($leadFormInput, 'step1.button_label', 'Get Free Valuation'),
                ],
                'step2' => [
                    'specs_label'     => data_get($leadFormInput, 'step2.specs_label', 'Regional Specs'),
                    'body_label'      => data_get($leadFormInput, 'step2.body_label', 'Body Type'),
                    'engine_label'    => data_get($leadFormInput, 'step2.engine_label', 'Engine Size'),
                    'mileage_label'   => data_get($leadFormInput, 'step2.mileage_label', 'Mileage (KM)'),
                    'condition_label' => data_get($leadFormInput, 'step2.condition_label', 'Overall Condition Matrix'),
                    'back_label'      => data_get($leadFormInput, 'step2.back_label', 'Back'),
                    'next_label'      => data_get($leadFormInput, 'step2.next_label', 'Next Stage'),
                ],
                'step3' => [
                    'name_label'    => data_get($leadFormInput, 'step3.name_label', 'Full Identity'),
                    'phone_label'   => data_get($leadFormInput, 'step3.phone_label', 'Mobile Number'),
                    'email_label'   => data_get($leadFormInput, 'step3.email_label', 'Email Address'),
                    'notes_label'   => data_get($leadFormInput, 'step3.notes_label', 'Additional Notes'),
                    'submit_label'  => data_get($leadFormInput, 'step3.submit_label', 'Request Free Valuation'),
                    'back_label'    => data_get($leadFormInput, 'step3.back_label', 'Back'),
                ],
            ];

        }
        
        // Footer settings
        $content['footer'] = [
            'background_color' => $request->input('footer_background_color') ?? data_get($content, 'footer.background_color', '#031629'),
        ];
        
        $navbarInput = $request->input('navbar', []);
        $content['navbar'] = [
            'phone'      => $request->input('navbar_phone') ?? data_get($content, 'navbar.phone', '+1 (234) 567 890'),
            'hours'      => $request->input('navbar_hours') ?? data_get($content, 'navbar.hours', 'Mon - Fri: 9:00 - 18:00'),
            'sticky'     => $request->boolean('navbar_sticky', data_get($content, 'navbar.sticky', true)),
            'glass'      => $request->boolean('navbar_glass', data_get($content, 'navbar.glass', true)),
            'bg_color'   => data_get($navbarInput, 'bg_color', data_get($content, 'navbar.bg_color', '#ffffff')),
            'text_color' => data_get($navbarInput, 'text_color', data_get($content, 'navbar.text_color', '#1d293d')),
        ];

        // Trust Badges
        $content['trust_badges_title'] = $request->input('trust_badges_title', data_get($content, 'trust_badges_title', 'We built our business on trust'));
        if ($request->has('trust_badges')) {
            $content['trust_badges'] = collect($request->input('trust_badges', []))
                ->map(fn($b) => [
                    'label'     => data_get($b, 'label', ''),
                    'icon'      => data_get($b, 'icon', 'star'),
                    'color'     => data_get($b, 'color', '#333333'),
                    'bg_color'  => data_get($b, 'bg_color', '#f1f5f9'),
                    'desc'      => data_get($b, 'desc', ''),
                ])->values()->all();
        }

        // Footer settings
        $content['footer'] = array_merge(data_get($content, 'footer', []), [
            'background_color' => $request->input('footer.background_color', data_get($content, 'footer.background_color', '#eef3f9')),
            'description'      => $request->input('footer.description', data_get($content, 'footer.description', '')),
            'address'          => $request->input('footer.address', data_get($content, 'footer.address', '')),
            'email'            => $request->input('footer.email', data_get($content, 'footer.email', '')),
            'phone'            => $request->input('footer.phone', data_get($content, 'footer.phone', '')),
            'copyright'        => $request->input('footer.copyright', data_get($content, 'footer.copyright', '')),
            'terms_url'        => $request->input('footer.terms_url', data_get($content, 'footer.terms_url', '#')),
            'privacy_url'      => $request->input('footer.privacy_url', data_get($content, 'footer.privacy_url', '#')),
            'cookies_url'      => $request->input('footer.cookies_url', data_get($content, 'footer.cookies_url', '#')),
            'social' => [
                'facebook'  => $request->input('footer.social.facebook', data_get($content, 'footer.social.facebook', '')),
                'instagram' => $request->input('footer.social.instagram', data_get($content, 'footer.social.instagram', '')),
                'whatsapp'  => $request->input('footer.social.whatsapp', data_get($content, 'footer.social.whatsapp', '')),
                'youtube'   => $request->input('footer.social.youtube', data_get($content, 'footer.social.youtube', '')),
            ],
        ]);

        // Footer quick links
        if ($request->has('footer_quick_links')) {
            $content['footer']['quick_links'] = collect($request->input('footer_quick_links', []))
                ->filter(fn($l) => !empty(data_get($l, 'label')))
                ->map(fn($l) => ['label' => data_get($l, 'label', ''), 'url' => data_get($l, 'url', '#')])
                ->values()->all();
        }

        // Footer pages (internal pages from page builder)
        if ($request->has('footer_pages')) {
            $content['footer']['pages'] = collect($request->input('footer_pages', []))
                ->filter(fn($p) => !empty(data_get($p, 'label')))
                ->map(fn($p) => ['label' => data_get($p, 'label', ''), 'url' => data_get($p, 'url', '#')])
                ->values()->all();
        }

        $page->update([
            'title' => $request->input('title'),
            'hero_image' => $heroCarImage,
            'content' => $content,
        ]);

        Cache::forget('homepage.cms.page');
        Cache::forget('homepage.featured.auctions');
        Cache::forget('homepage.stats');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Infrastructure Synchronized Successfully!',
            ]);
        }

        return redirect()->back()->with('success', 'Homepage updated successfully!');
    }

    private function sanitizeHeroHtml(?string $value): string
    {
        if (empty($value)) {
            return '';
        }

        $allowedTags = ['span', 'strong', 'em', 'u', 'br'];
        $allowedAttributes = ['style'];

        $document = new \DOMDocument();
        libxml_use_internal_errors(true);
        $document->loadHTML('<div>' . $value . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $this->cleanNode($document->documentElement, $allowedTags, $allowedAttributes);

        $innerHtml = $document->saveHTML($document->documentElement);
        // strip wrapper div
        return preg_replace('~^<div>(.*)</div>$~s', '$1', $innerHtml) ?: '';
    }

    private function cleanNode(\DOMNode $node, array $allowedTags, array $allowedAttributes): void
    {
        if ($node->nodeType === XML_ELEMENT_NODE) {
            /** @var \DOMElement $element */
            $element = $node;
            if (!in_array($element->tagName, $allowedTags, true)) {
                $fragment = $element->ownerDocument->createDocumentFragment();
                while ($element->childNodes->length) {
                    $fragment->appendChild($element->childNodes->item(0));
                }
                $element->parentNode?->replaceChild($fragment, $element);
                return;
            }

            foreach (iterator_to_array($element->attributes) as $attr) {
                if (!in_array($attr->name, $allowedAttributes, true)) {
                    $element->removeAttributeNode($attr);
                    continue;
                }

                if ($attr->name === 'style') {
                    $element->setAttribute('style', preg_replace('~[^a-zA-Z0-9\-:, #;.]~', '', $attr->value));
                }
            }
        }

        foreach (iterator_to_array($node->childNodes) as $child) {
            $this->cleanNode($child, $allowedTags, $allowedAttributes);
        }
    }
}
