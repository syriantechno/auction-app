<?php $__env->startSection('title', 'SEO Settings & Integrations'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto">
    <header class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-black text-gray-900">SEO Settings & Integrations</h1>
                <p class="text-gray-600 mt-2">Configure your SEO tools and notifications</p>
            </div>
            <a href="<?php echo e(route('admin.seo.dashboard')); ?>" class="px-4 py-2 bg-gray-600 text-white rounded-lg font-medium hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
            </a>
        </div>
    </header>

    <form method="POST" action="<?php echo e(route('admin.seo.settings.update')); ?>" class="space-y-8">
        <?php echo csrf_field(); ?>
        
        
        <?php if($errors->any()): ?>
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <h4 class="text-red-800 font-medium mb-2">Errors:</h4>
            <ul class="list-disc list-inside text-sm text-red-600">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>
        
        
        <?php if(app()->environment('local')): ?>
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <h4 class="text-yellow-800 font-medium mb-2">Debug Info:</h4>
            <pre class="text-xs text-yellow-700 overflow-x-auto"><?php echo e(print_r($settings->toArray(), true)); ?></pre>
        </div>
        <?php endif; ?>
        
        <!-- AgentRouter API -->
        <div class="bg-white rounded-md border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">AgentRouter API</h3>
                    <p class="text-sm text-gray-600 mt-1">Configure the AgentRouter OpenAI-compatible API for SEO generation</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-robot text-purple-600"></i>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">API Provider</label>
                    <select name="agent_router_provider" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white">
                        <option value="openai-compatible" <?php echo e(($settings->agent_router_provider ?? 'openai-compatible') === 'openai-compatible' ? 'selected' : ''); ?>>OpenAI Compatible</option>
                        <option value="openai" <?php echo e(($settings->agent_router_provider ?? '') === 'openai' ? 'selected' : ''); ?>>OpenAI Direct</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Select your AI provider</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">API Key</label>
                    <input type="text" name="agent_router_api_key" value="<?php echo e($settings->agent_router_api_key); ?>" 
                           placeholder="sk-..." autocomplete="off" spellcheck="false"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 font-mono text-sm">
                    <p class="text-xs text-gray-500 mt-1">Your API key from the provider</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Base URL</label>
                    <input type="text" name="agent_router_base_url" value="<?php echo e($settings->agent_router_base_url); ?>" 
                           placeholder="https://agentrouter.org/v1" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <p class="text-xs text-gray-500 mt-1">API endpoint URL</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">AI Model</label>
                    <select name="agent_router_model" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white">
                        <?php $__currentLoopData = config('ai_seo.agent_router.supported_models', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modelValue => $modelLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($modelValue); ?>" <?php echo e(($settings->agent_router_model ?? config('ai_seo.agent_router.model')) === $modelValue ? 'selected' : ''); ?>>
                                <?php echo e($modelLabel); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Choose from supported AgentRouter models</p>
                </div>
            </div>

            <div class="flex items-center justify-between mt-6">
                <div class="flex items-center space-x-2">
                    <?php if($settings->isAgentRouterConfigured()): ?>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i> Configured
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Not Configured
                        </span>
                    <?php endif; ?>
                </div>

                <div class="flex items-center gap-3">
                    <button type="button" id="verify-agent-router-key" class="px-4 py-2 bg-slate-700 text-white rounded-lg text-sm font-medium hover:bg-slate-800 transition-colors">
                        <i class="fas fa-fingerprint mr-2"></i> Verify Saved Key
                    </button>

                    <button type="button" id="test-agent-router" class="px-4 py-2 bg-purple-600 text-white rounded-lg text-sm font-medium hover:bg-purple-700 transition-colors">
                        <i class="fas fa-plug mr-2"></i> Test Connection
                    </button>
                </div>
            </div>

            <div id="api-key-verify-result" class="mt-4 hidden rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700"></div>
            <div id="agent-router-test-result" class="mt-4 hidden rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700"></div>
        </div>

        <!-- Google Services -->
        <div class="bg-white rounded-md border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Google Services</h3>
                    <p class="text-sm text-gray-600 mt-1">Configure Google Analytics and Search Console</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fab fa-google text-blue-600"></i>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Google Analytics ID</label>
                    <input type="text" name="google_analytics_id" value="<?php echo e($settings->google_analytics_id); ?>" 
                           placeholder="G-XXXXXXXXXX" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <p class="text-xs text-gray-500 mt-1">Format: G-XXXXXXXXXX or UA-XXXXXXXX-X</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Console API Key</label>
                    <input type="password" name="google_search_console_api_key" value="<?php echo e($settings->google_search_console_api_key); ?>" 
                           placeholder="Enter API key" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <p class="text-xs text-gray-500 mt-1">For automatic indexing submission</p>
                </div>
            </div>

            <div class="flex items-center space-x-6 mt-6">
                <input type="hidden" name="auto_submit_google" value="0">
                <label class="flex items-center">
                    <input type="checkbox" name="auto_submit_google" value="1" <?php echo e($settings->auto_submit_google ? 'checked' : ''); ?> 
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Auto-submit to Google</span>
                </label>
            </div>
        </div>

        <!-- Bing Services -->
        <div class="bg-white rounded-md border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Bing Services</h3>
                    <p class="text-sm text-gray-600 mt-1">Configure Bing Webmaster Tools</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fab fa-microsoft text-blue-600"></i>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bing Webmaster API Key</label>
                <input type="password" name="bing_webmaster_api_key" value="<?php echo e($settings->bing_webmaster_api_key); ?>" 
                       placeholder="Enter API key" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <p class="text-xs text-gray-500 mt-1">For automatic indexing submission to Bing</p>
            </div>

            <div class="flex items-center space-x-6 mt-6">
                <label class="flex items-center">
                    <input type="hidden" name="auto_submit_bing" value="0">
                <input type="checkbox" name="auto_submit_bing" value="1" <?php echo e($settings->auto_submit_bing ? 'checked' : ''); ?> 
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Auto-submit to Bing</span>
                </label>
            </div>
        </div>

        <!-- WhatsApp Agent -->
        <div class="bg-white rounded-md border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">WhatsApp Agent</h3>
                    <p class="text-sm text-gray-600 mt-1">Receive SEO notifications via WhatsApp</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fab fa-whatsapp text-green-600"></i>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp API Key</label>
                    <input type="password" name="whatsapp_agent_api_key" value="<?php echo e($settings->whatsapp_agent_api_key); ?>" 
                           placeholder="Enter API key" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <p class="text-xs text-gray-500 mt-1">From your WhatsApp Business API provider</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Target Phone Number</label>
                    <input type="text" name="whatsapp_agent_phone" value="<?php echo e($settings->whatsapp_agent_phone); ?>" 
                           placeholder="+1234567890" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <p class="text-xs text-gray-500 mt-1">Include country code (e.g., +1234567890)</p>
                </div>
            </div>

            <div class="flex items-center justify-between mt-6">
                <input type="hidden" name="whatsapp_notifications" value="0">
                <label class="flex items-center">
                    <input type="checkbox" name="whatsapp_notifications" value="1" <?php echo e($settings->whatsapp_notifications ? 'checked' : ''); ?> 
                           class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                    <span class="ml-2 text-sm text-gray-700">Enable WhatsApp notifications</span>
                </label>

                <button type="button" id="test-whatsapp" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                    <i class="fas fa-paper-plane mr-2"></i> Test Connection
                </button>
            </div>

            <?php if($settings->whatsapp_notifications): ?>
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Notification Types</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <?php $__currentLoopData = $settings->getNotificationTypesList(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="flex items-center">
                        <input type="checkbox" name="notification_types[]" value="<?php echo e($key); ?>" 
                               <?php echo e(in_array($key, $settings->notification_types ?? []) ? 'checked' : ''); ?>

                               class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <span class="ml-2 text-sm text-gray-700"><?php echo e($label); ?></span>
                    </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Rank Tracking -->
        <div class="bg-white rounded-md border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Rank Tracking</h3>
                    <p class="text-sm text-gray-600 mt-1">Monitor keyword rankings over time</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600"></i>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Keywords to Track</label>
                <div class="space-y-2">
                    <?php $__currentLoopData = $settings->ranking_track_keywords ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyword): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center space-x-2">
                        <input type="text" name="ranking_track_keywords[]" value="<?php echo e($keyword); ?>" 
                               class="flex-1 border border-gray-300 rounded-lg px-3 py-2">
                        <button type="button" class="remove-keyword text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center space-x-2">
                        <input type="text" name="ranking_track_keywords[]" placeholder="Add new keyword..." 
                               class="flex-1 border border-gray-300 rounded-lg px-3 py-2">
                        <button type="button" id="add-keyword" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-1">Enter keywords to track rankings for</p>
            </div>
        </div>

        <!-- General Settings -->
        <div class="bg-white rounded-md border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">General Settings</h3>
                    <p class="text-sm text-gray-600 mt-1">Configure SEO monitoring and alerts</p>
                </div>
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-cog text-gray-600"></i>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SEO Score Alert Threshold</label>
                    <input type="number" name="alert_threshold" value="<?php echo e($settings->alert_threshold); ?>" 
                           min="0" max="100" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <p class="text-xs text-gray-500 mt-1">Send alert when score falls below this value</p>
                </div>

                <div class="flex items-center space-x-6">
                    <input type="hidden" name="daily_reports" value="0">
                    <label class="flex items-center">
                        <input type="checkbox" name="daily_reports" value="1" <?php echo e($settings->daily_reports ? 'checked' : ''); ?> 
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Daily SEO reports</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex justify-end space-x-4">
            <a href="<?php echo e(route('admin.seo.dashboard')); ?>" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-2"></i> Save Settings
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Verify saved AgentRouter API key
    document.getElementById('verify-agent-router-key')?.addEventListener('click', async function() {
        const btn = this;
        const resultBox = document.getElementById('api-key-verify-result');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Verifying...';

        try {
            const response = await fetch('/admin/seo/verify-agent-router-key', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();

            if (result.success) {
                resultBox.className = 'mt-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800';
                resultBox.innerHTML = `
                    <div class="font-semibold mb-1">API key saved successfully.</div>
                    <div>Source: <span class="font-mono">${result.source}</span></div>
                    <div>Database length: <span class="font-mono">${result.database_length}</span></div>
                    <div>Effective length: <span class="font-mono">${result.effective_length}</span></div>
                    <div>Masked preview: <span class="font-mono">${result.masked_preview}</span></div>
                `;
            } else {
                resultBox.className = 'mt-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800';
                resultBox.innerHTML = `<div class="font-semibold">${result.message}</div>`;
            }

            resultBox.classList.remove('hidden');
        } catch (error) {
            resultBox.className = 'mt-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800';
            resultBox.textContent = 'Verification failed: ' + error.message;
            resultBox.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-fingerprint mr-2"></i> Verify Saved Key';
        }
    });

    // Test AgentRouter connection
    document.getElementById('test-agent-router')?.addEventListener('click', async function() {
        const btn = this;
        const resultBox = document.getElementById('agent-router-test-result');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Testing...';

        try {
            console.log('Starting test connection...');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            console.log('CSRF Token:', csrfToken ? 'Found' : 'Not found');
            
            const response = await fetch('/admin/seo/test-agent-router', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || ''
                }
            });
            
            console.log('Response status:', response.status);

            const result = await response.json();
            console.log('Result:', result);

            if (result.success) {
                btn.innerHTML = '<i class="fas fa-check mr-2"></i> Success!';
                btn.classList.remove('bg-purple-600', 'hover:bg-purple-700');
                btn.classList.add('bg-green-500');

                resultBox.className = 'mt-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800';
                resultBox.innerHTML = `
                    <div class="font-semibold mb-1">Connection successful.</div>
                    <div>Model: <span class="font-mono">${result.details?.model ?? 'N/A'}</span></div>
                    <div>Base URL: <span class="font-mono">${result.details?.base_url ?? 'N/A'}</span></div>
                    <div>Generated title: <span class="font-mono">${result.details?.generated_title ?? 'N/A'}</span></div>
                `;
                resultBox.classList.remove('hidden');
            } else {
                btn.innerHTML = '<i class="fas fa-times mr-2"></i> Failed';
                btn.classList.remove('bg-purple-600', 'hover:bg-purple-700');
                btn.classList.add('bg-red-500');

                const serviceError = result.details?.service_error || {};
                const errorLines = [
                    `<div class="font-semibold mb-1">${result.message ?? 'Connection failed.'}</div>`,
                    serviceError.provider ? `<div>Provider: <span class="font-mono">${serviceError.provider}</span></div>` : '',
                    serviceError.status ? `<div>Status: <span class="font-mono">${serviceError.status}</span></div>` : '',
                    serviceError.error ? `<div>Error: <span class="font-mono">${serviceError.error}</span></div>` : '',
                    serviceError.body_preview ? `<div class="mt-2 break-words"><span class="font-semibold">Body:</span> <span class="font-mono text-xs">${serviceError.body_preview}</span></div>` : '',
                ].filter(Boolean).join('');

                resultBox.className = 'mt-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800';
                resultBox.innerHTML = errorLines || `<div class="font-semibold">${result.message ?? 'Connection failed.'}</div>`;
                resultBox.classList.remove('hidden');
                console.error('Test Connection Error:', result);
            }

            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-plug mr-2"></i> Test Connection';
                btn.classList.remove('bg-green-500', 'bg-red-500');
                btn.classList.add('bg-purple-600', 'hover:bg-purple-700');
            }, 3000);

        } catch (error) {
            console.error('Catch Error:', error);
            console.error('Error details:', error.message);
            
            btn.innerHTML = '<i class="fas fa-times mr-2"></i> Error';
            btn.classList.remove('bg-purple-600', 'hover:bg-purple-700');
            btn.classList.add('bg-red-500');

            resultBox.className = 'mt-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800';
            resultBox.innerHTML = `<div class="font-semibold">JavaScript Error</div><div class="font-mono text-xs break-words mt-1">${error.message}</div>`;
            resultBox.classList.remove('hidden');

            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-plug mr-2"></i> Test Connection';
                btn.classList.remove('bg-red-500');
                btn.classList.add('bg-purple-600', 'hover:bg-purple-700');
            }, 3000);
        }
    });

    // Test WhatsApp connection
    document.getElementById('test-whatsapp')?.addEventListener('click', async function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Testing...';

        try {
            const response = await fetch('/admin/seo/test-whatsapp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();

            if (result.success) {
                btn.innerHTML = '<i class="fas fa-check mr-2"></i> Success!';
                btn.classList.remove('bg-green-600', 'hover:bg-green-700');
                btn.classList.add('bg-green-500');
            } else {
                btn.innerHTML = '<i class="fas fa-times mr-2"></i> Failed';
                btn.classList.remove('bg-green-600', 'hover:bg-green-700');
                btn.classList.add('bg-red-500');
            }

            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Test Connection';
                btn.classList.remove('bg-green-500', 'bg-red-500');
                btn.classList.add('bg-green-600', 'hover:bg-green-700');
            }, 3000);

        } catch (error) {
            btn.innerHTML = '<i class="fas fa-times mr-2"></i> Error';
            btn.classList.remove('bg-green-600', 'hover:bg-green-700');
            btn.classList.add('bg-red-500');

            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Test Connection';
                btn.classList.remove('bg-red-500');
                btn.classList.add('bg-green-600', 'hover:bg-green-700');
            }, 3000);
        }
    });

    // Add keyword functionality
    document.getElementById('add-keyword')?.addEventListener('click', function() {
        const container = this.closest('.space-y-2');
        const newKeywordDiv = document.createElement('div');
        newKeywordDiv.className = 'flex items-center space-x-2';
        newKeywordDiv.innerHTML = `
            <input type="text" name="ranking_track_keywords[]" placeholder="Enter keyword..." 
                   class="flex-1 border border-gray-300 rounded-lg px-3 py-2">
            <button type="button" class="remove-keyword text-red-600 hover:text-red-800">
                <i class="fas fa-trash"></i>
            </button>
        `;
        container.insertBefore(newKeywordDiv, this.parentElement);
    });

    // Remove keyword functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-keyword')) {
            e.target.closest('.flex').remove();
        }
    });
});
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\auction_app\resources\views/admin/seo/settings.blade.php ENDPATH**/ ?>