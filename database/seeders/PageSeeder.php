<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Page::updateOrCreate(
            ['slug' => 'about-us'],
            [
                'title' => 'The Motor Bazar Story',
                'meta_description' => 'Redefining the luxury car auction experience with cutting-edge technology and ultimate transparency.',
                'hero_image' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&q=80&w=1920',
                'content' => '
                    <div class="space-y-12">
                        <section>
                            <h2 class="text-3xl font-black mb-6">Our Mission</h2>
                            <p class="text-gray-600 leading-relaxed text-lg">
                                At <strong>Motor Bazar</strong>, we believe every premium vehicle deserves a stage where it can be appreciated by true enthusiasts. We didn\'t just build another auction site; we created a luxury ecosystem where collectors and car lovers meet to trade with absolute confidence.
                            </p>
                        </section>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 not-prose">
                            <div class="bg-gray-50 p-10 rounded-[32px] border border-gray-100">
                                <div class="w-14 h-14 bg-bazar-500 rounded-2xl flex items-center justify-center text-white mb-6 shadow-xl shadow-bazar-500/20">
                                    <i data-lucide="shield-check" class="w-7 h-7"></i>
                                </div>
                                <h3 class="text-xl font-black text-deep-900 mb-2">Verified Integrity</h3>
                                <p class="text-gray-500 text-sm font-medium leading-relaxed">Every car undergoes a rigorous 200-point inspection before it even touches our platform.</p>
                            </div>
                            
                            <div class="bg-deep-900 p-10 rounded-[32px] border border-white/5 shadow-2xl">
                                <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-bazar-500 mb-6">
                                    <i data-lucide="zap" class="w-7 h-7"></i>
                                </div>
                                <h3 class="text-xl font-black text-white mb-2">Real-time Trading</h3>
                                <p class="text-gray-400 text-sm font-medium leading-relaxed">Our advanced tech stack ensures zero-latency bidding, making every second count in the heat of an auction.</p>
                            </div>
                        </div>

                        <section>
                            <h2 class="text-3xl font-black mb-6 italic">Why Choose Us?</h2>
                            <p class="text-gray-600 leading-relaxed text-lg mb-8">
                                We combine decades of automotive expertise with silicon valley technology. Our founders are collectors themselves, understanding the pain points of traditional auctions.
                            </p>
                            <blockquote class="border-l-8 border-bazar-500 bg-gray-50 p-12 rounded-3xl">
                                <p class="text-2xl font-bold italic text-deep-900 mb-4">"We don\'t just sell cars, we preserve history and fuel passion. Every auction is a story waiting to be told."</p>
                                <cite class="text-sm font-black uppercase text-bazar-500 tracking-widest">— CEO, Motor Bazar</cite>
                            </blockquote>
                        </section>
                    </div>
                ',
                'is_active' => true,
                'is_published' => true
            ]
        );
    }
}
