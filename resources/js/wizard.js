/**
 * Bazar Selling Wizard Engine
 * Centralized logic for the multi-step car selling funnel.
 */

export function initBazarWizard(config) {
    const wizard = document.getElementById('sellCarWizard');
    if (!wizard) return;

    const {
        brandModelMap = {},
        branchCoords = [25.1384, 55.2285],
        startStep = 0,
        mapProvider = 'google'
    } = config;

    const steps = Array.from(wizard.querySelectorAll('[data-step]'));
    const btnBacks = Array.from(wizard.querySelectorAll('[data-action="back"]'));
    const btnNexts = Array.from(wizard.querySelectorAll('[data-action="next"]'));
    const btnSubmit = wizard.querySelector('[data-action="submit"]') || wizard.querySelector('button[type="submit"]');

    const brandHubToggle = document.getElementById('brandHubToggle');
    const brandHubDrawer = document.getElementById('brandHubDrawer');
    const brandHubSearch = document.getElementById('brandHubSearch');
    const closeBrandHub = document.getElementById('closeBrandHub');
    const brandHubLabel = document.getElementById('brandHubLabel');
    const brandHubIcon = document.getElementById('brandHubIcon');
    const brandHubIconImg = document.getElementById('brandHubIconImg');
    const makeSelect = document.getElementById('sellCarMakeSelect_dynamic');
    const brandHubOptions = Array.from(document.querySelectorAll('[data-brand-hub-value]'));
    const popularBrandPicks = Array.from(document.querySelectorAll('[data-brand-pick]'));
    const resetBrandHub = document.getElementById('resetBrandHub');

    const modelHubToggle = document.getElementById('modelHubToggle');
    const modelHubDrawer = document.getElementById('modelHubDrawer');
    const modelHubSearch = document.getElementById('modelHubSearch');
    const modelHubLabel = document.getElementById('modelHubLabel');
    const modelListContainer = document.getElementById('modelListContainer');
    const modelInput = document.getElementById('sellCarModelInput');

    const yearHubToggle = document.getElementById('yearHubToggle');
    const yearHubDrawer = document.getElementById('yearHubDrawer');
    const yearHubLabel = document.getElementById('yearHubLabel');
    const yearInput = document.getElementById('sellCarYearInput');
    const yearPicks = document.querySelectorAll('.year-pick');

    const gccInput = document.getElementById('sellCarGccInput');
    const gccPicks = document.querySelectorAll('.gcc-pick');
    const gccHubToggle = document.getElementById('gccHubToggle');
    const gccHubDrawer = document.getElementById('gccHubDrawer');
    const gccHubLabel = document.getElementById('gccHubLabel');

    const bodyHubToggle = document.getElementById('bodyHubToggle');
    const bodyHubDrawer = document.getElementById('bodyHubDrawer');
    const bodyHubLabel = document.getElementById('bodyHubLabel');
    const bodyInput = document.getElementById('sellCarBodyInput');
    const bodyPicks = document.querySelectorAll('.body-pick');

    const engineHubToggle = document.getElementById('engineHubToggle');
    const engineHubDrawer = document.getElementById('engineHubDrawer');
    const engineHubLabel = document.getElementById('engineHubLabel');
    const engineInput = document.getElementById('sellCarEngineInput');
    const enginePicks = document.querySelectorAll('.engine-pick');

    const mileageHubToggle = document.getElementById('mileageHubToggle');
    const mileageHubDrawer = document.getElementById('mileageHubDrawer');
    const mileageHubLabel = document.getElementById('mileageHubLabel');
    const mileageInput = document.getElementById('sellCarMileageInput');
    const mileagePicks = document.querySelectorAll('.mileage-pick');

    const trimInput = document.getElementById('sellCarTrimInput');
    const trimPicks = document.querySelectorAll('.trim-pick');
    const paintInput = document.getElementById('sellCarPaintInput');
    const paintPicks = document.querySelectorAll('.paint-pick');
    const conditionInput = document.getElementById('sellCarConditionInput');
    const conditionPicks = document.querySelectorAll('.condition-pick');

    const dateHubToggle = document.getElementById('dateHubToggle');
    const dateHubDrawer = document.getElementById('dateHubDrawer');
    const dateHubLabel = document.getElementById('dateHubLabel');
    const dateInput = document.getElementById('inspection_date_input');
    const datePicks = document.querySelectorAll('.date-pick');

    const timeHubToggle = document.getElementById('timeHubToggle');
    const timeHubDrawer = document.getElementById('timeHubDrawer');
    const timeHubLabel = document.getElementById('timeHubLabel');
    const timeInput = document.getElementById('inspection_time_input');
    const timePicks = document.querySelectorAll('.time-pick');

    let currentIdx = startStep;

    const normalizeMake = (value = '') => String(value).toLowerCase().replace(/[^a-z0-9]+/g, '');

    function parseModels(raw) {
        if (!raw) return [];
        try {
            const parsed = JSON.parse(raw);
            return Array.isArray(parsed) ? parsed : [];
        } catch (e) {
            return [];
        }
    }

    function getModelList(makeValue) {
        const key = normalizeMake(makeValue);
        return brandModelMap[key] || brandModelMap.__all__ || [];
    }

    function populateModels(models, selectedModel = '') {
        if (!modelListContainer || !modelHubToggle) return;

        modelListContainer.innerHTML = '';

        if (!models.length) {
            modelHubToggle.disabled = true;
            modelHubToggle.classList.add('bg-slate-50', 'text-slate-400');
            modelHubToggle.classList.remove('bg-white', 'text-slate-900');
            modelHubLabel.textContent = 'No models found';
            return;
        }

        modelHubToggle.disabled = false;
        modelHubToggle.classList.remove('bg-slate-50', 'text-slate-400');
        modelHubToggle.classList.add('bg-white', 'text-slate-900');

        if (!selectedModel) {
            modelHubLabel.textContent = 'Select Model';
        }

        models.forEach(model => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'w-full p-2.5 rounded-[1rem] border border-transparent hover:bg-slate-50 hover:border-slate-200 transition-all text-left group flex items-center gap-3';
            btn.setAttribute('data-model-value', model);

            const bullet = document.createElement('span');
            bullet.className = 'w-1.5 h-1.5 rounded-full bg-slate-200 group-hover:bg-orange-400 transition-colors shrink-0';
            
            const text = document.createElement('span');
            text.className = 'text-sm font-semibold text-slate-700 group-hover:text-slate-900 truncate';
            text.textContent = model;

            btn.appendChild(bullet);
            btn.appendChild(text);

            btn.addEventListener('click', () => {
                setSelectedModel(model);
                toggleModelHub(false);
            });

            modelListContainer.appendChild(btn);
        });

        if (selectedModel) {
            setSelectedModel(selectedModel);
        }
    }

    function setSelectedModel(value) {
        if (modelInput) modelInput.value = value || '';
        if (modelHubLabel) {
            modelHubLabel.textContent = value || 'Select Model';
            modelHubLabel.classList.toggle('text-slate-400', !value);
            modelHubLabel.classList.toggle('text-slate-900', !!value);
        }
        
        const modelBtns = Array.from(modelListContainer?.querySelectorAll('[data-model-value]') || []);
        modelBtns.forEach(btn => {
            const isSelected = btn.getAttribute('data-model-value') === value;
            btn.classList.toggle('bg-orange-50', isSelected);
            btn.classList.toggle('border-orange-200', isSelected);
            btn.querySelector('.text-sm')?.classList.toggle('text-slate-900', isSelected);
            btn.querySelector('.rounded-full')?.classList.toggle('bg-[#ff6900]', isSelected);
        });
    }

    function setSelectedBrand(value, logo = '', preserveModel = false, models = []) {
        if (makeSelect) {
            makeSelect.value = value || '';
        }

        if (brandHubLabel) {
            brandHubLabel.textContent = value || 'Select Brand';
            brandHubLabel.classList.toggle('text-slate-400', !value);
            brandHubLabel.classList.toggle('text-slate-900', !!value);
        }

        if (brandHubIcon && brandHubIconImg) {
            if (logo) {
                brandHubIconImg.src = logo;
                brandHubIconImg.alt = value || 'Brand logo';
                brandHubIcon.classList.remove('hidden');
            } else {
                brandHubIcon.classList.add('hidden');
            }
        }

        brandHubOptions.forEach(btn => {
            const btnValue = btn.getAttribute('data-brand-hub-value') || '';
            btn.classList.toggle('bg-orange-50', btnValue === value);
            btn.classList.toggle('border-orange-200', btnValue === value);
            btn.querySelector('.text-sm')?.classList.toggle('text-slate-900', btnValue === value);
        });

        const resolvedModels = Array.isArray(models) && models.length ? models : getModelList(value);

        if (!preserveModel) {
            setSelectedModel('');
        }

        populateModels(resolvedModels, preserveModel ? (modelInput?.value || '') : '');
    }

    function toggleBrandHub(force = null) {
        if (!brandHubDrawer) return;
        const shouldOpen = force === null ? brandHubDrawer.classList.contains('hidden') : force;
        brandHubDrawer.classList.toggle('hidden', !shouldOpen);
        if (shouldOpen) {
            toggleModelHub(false);
            toggleYearHub(false);
            toggleDateHub(false);
            toggleTimeHub(false);
            if (brandHubSearch) brandHubSearch.focus();
        }
    }

    function toggleModelHub(force = null) {
        if (!modelHubDrawer || modelHubToggle.disabled) return;
        const shouldOpen = force === null ? modelHubDrawer.classList.contains('hidden') : force;
        modelHubDrawer.classList.toggle('hidden', !shouldOpen);
        if (shouldOpen) {
            toggleBrandHub(false);
            toggleYearHub(false);
            toggleDateHub(false);
            toggleTimeHub(false);
            if (modelHubSearch) modelHubSearch.focus();
        }
    }

    function toggleYearHub(force = null) {
        if (!yearHubDrawer) return;
        const shouldOpen = force === null ? yearHubDrawer.classList.contains('hidden') : force;
        yearHubDrawer.classList.toggle('hidden', !shouldOpen);
        if (shouldOpen) {
            toggleBrandHub(false);
            toggleModelHub(false);
            toggleDateHub(false);
            toggleTimeHub(false);
        }
    }

    function toggleDateHub(force = null) {
        if (!dateHubDrawer) return;
        const shouldOpen = force === null ? dateHubDrawer.classList.contains('hidden') : force;
        dateHubDrawer.classList.toggle('hidden', !shouldOpen);
        if (shouldOpen) {
            toggleBrandHub(false);
            toggleModelHub(false);
            toggleYearHub(false);
            toggleTimeHub(false);
        }
    }

    function toggleTimeHub(force = null) {
        if (!timeHubDrawer) return;
        const shouldOpen = force === null ? timeHubDrawer.classList.contains('hidden') : force;
        timeHubDrawer.classList.toggle('hidden', !shouldOpen);
        if (shouldOpen) {
            toggleBrandHub(false);
            toggleModelHub(false);
            toggleYearHub(false);
            toggleDateHub(false);
        }
    }

    function toggleGccHub(force = null) {
        if (!gccHubDrawer) return;
        const shouldOpen = force === null ? gccHubDrawer.classList.contains('hidden') : force;
        gccHubDrawer.classList.toggle('hidden', !shouldOpen);
        if (shouldOpen) {
            toggleBrandHub(false);
            toggleModelHub(false);
            toggleBodyHub(false);
            toggleEngineHub(false);
            toggleMileageHub(false);
        }
    }

    function toggleBodyHub(force = null) {
        if (!bodyHubDrawer) return;
        const shouldOpen = force === null ? bodyHubDrawer.classList.contains('hidden') : force;
        bodyHubDrawer.classList.toggle('hidden', !shouldOpen);
        if (shouldOpen) {
            toggleBrandHub(false);
            toggleModelHub(false);
            toggleEngineHub(false);
            toggleMileageHub(false);
        }
    }

    function toggleEngineHub(force = null) {
        if (!engineHubDrawer) return;
        const shouldOpen = force === null ? engineHubDrawer.classList.contains('hidden') : force;
        engineHubDrawer.classList.toggle('hidden', !shouldOpen);
        if (shouldOpen) {
            toggleBrandHub(false);
            toggleModelHub(false);
            toggleBodyHub(false);
            toggleMileageHub(false);
        }
    }

    function toggleMileageHub(force = null) {
        if (!mileageHubDrawer) return;
        const shouldOpen = force === null ? mileageHubDrawer.classList.contains('hidden') : force;
        mileageHubDrawer.classList.toggle('hidden', !shouldOpen);
        if (shouldOpen) {
            toggleBrandHub(false);
            toggleModelHub(false);
            toggleBodyHub(false);
            toggleEngineHub(false);
        }
    }

    function validateStep(stepIndex) {
        const step = steps[stepIndex];
        if (!step) return true;

        let valid = true;
        const requiredFields = Array.from(step.querySelectorAll('[required]'));

        requiredFields.forEach(field => {
            if (field.disabled) return;
            const value = typeof field.value === 'string' ? field.value.trim() : '';
            if (!value) {
                field.classList.add('ring-2', 'ring-red-400');
                valid = false;
            } else {
                field.classList.remove('ring-2', 'ring-red-400');
            }
        });

        if (stepIndex === 0) {
            const hasMake = !!makeSelect?.value;
            const hasModel = !!modelInput?.value;
            const hasYear = !!yearInput?.value;
            if (!hasMake || !hasModel || !hasYear) {
                valid = false;
                if (brandHubToggle && !hasMake) brandHubToggle.classList.add('ring-2', 'ring-red-400');
                if (modelHubToggle && !hasModel) modelHubToggle.classList.add('ring-2', 'ring-red-400');
                if (yearHubToggle && !hasYear) yearHubToggle.classList.add('ring-2', 'ring-red-400');
                setTimeout(() => {
                    [brandHubToggle, modelHubToggle, yearHubToggle].forEach(el => el?.classList.remove('ring-2', 'ring-red-400'));
                }, 800);
            }
        }

        if (stepIndex === 1) {
            const hasGcc = !!gccInput?.value;
            const hasBody = !!bodyInput?.value;
            const hasEngine = !!engineInput?.value;
            const hasMileage = !!mileageInput?.value;
            if (!hasGcc || !hasBody || !hasEngine || !hasMileage) {
                valid = false;
                window.BazarToast.warn('Please complete all vehicle specifications before continuing.');
            }
        }

        if (stepIndex === 2) {
            const hasDate  = !!document.getElementById('inspection_date_input')?.value;
            const hasTime  = !!document.getElementById('inspection_time_input')?.value;
            const hasName  = !!wizard.querySelector('[name="name"]')?.value;
            const hasPhone = !!wizard.querySelector('[name="phone"]')?.value;

            if (!hasName || !hasPhone) {
                valid = false;
                window.BazarToast.warn('Please enter your name and phone number.');
            } else if (!hasDate || !hasTime) {
                valid = false;
                window.BazarToast.warn('Please select an appointment date and time.');
            }
        }

        return valid;
    }

    function updateUI() {
        steps.forEach((step, i) => {
            step.classList.toggle('hidden', i !== currentIdx);
        });

        btnBacks.forEach(btn => btn.classList.toggle('hidden', currentIdx === 0));
        if (btnSubmit) btnSubmit.classList.toggle('hidden', currentIdx !== 2);

        if (currentIdx === 2 && !window._homeLeafletMap) {
            setTimeout(() => initHomeLeaflet(), 50);
        } else if (currentIdx === 2 && window._homeLeafletMap) {
            setTimeout(() => window._homeLeafletMap.invalidateSize(), 100);
        }

        // Wizard title updates
        const w1 = document.getElementById('wizard-title-w1');
        const w2 = document.getElementById('wizard-title-w2');
        const w3 = document.getElementById('wizard-title-w3');
        if (w1 && w2 && w3) {
            const orange = 'text-[#ff6900]';
            const muted  = 'text-slate-300';
            [w1, w2, w3].forEach(w => w.classList.remove(orange, muted));
            if (currentIdx === 0) { w1.classList.add(orange); w2.classList.add(muted); w3.classList.add(muted); }
            if (currentIdx === 1) { w1.classList.add(muted);  w2.classList.add(orange); w3.classList.add(muted); }
            if (currentIdx === 2) { w1.classList.add(muted);  w2.classList.add(muted);  w3.classList.add(orange); }
        }
    }

    function setInspectionType(type) {
        const input = document.getElementById('inspectionTypeInput');
        const btnBranch = document.getElementById('btnTabBranch');
        const btnHome = document.getElementById('btnTabHome');
        const mapSearch = document.getElementById('mapSearchContainer');
        const mapBranch = document.getElementById('mapBranchInfo');
        if (!input || !btnBranch || !btnHome) return;

        input.value = type;
        if (type === 'branch') {
            btnBranch.classList.remove('bg-slate-50', 'text-slate-400', 'border-transparent');
            btnBranch.classList.add('bg-white', 'text-slate-900', 'border-[#FF6900]');
            btnHome.classList.remove('bg-white', 'text-slate-900', 'border-[#FF6900]');
            btnHome.classList.add('bg-slate-50', 'text-slate-400', 'border-transparent');
            mapSearch.classList.add('hidden');
            mapBranch.classList.remove('hidden');
        } else {
            btnHome.classList.remove('bg-slate-50', 'text-slate-400', 'border-transparent');
            btnHome.classList.add('bg-white', 'text-[#FF6900]', 'border-[#FF6900]');
            btnBranch.classList.remove('bg-white', 'text-[#FF6900]', 'border-[#FF6900]');
            btnBranch.classList.add('bg-slate-50', 'text-slate-400', 'border-transparent');
            mapSearch.classList.remove('hidden');
            mapBranch.classList.add('hidden');
            if (window._homeLeafletMap) setTimeout(() => window._homeLeafletMap.invalidateSize(), 150);
        }
    }
    window.setInspectionType = setInspectionType;

    // ── Event Listeners ──
    if (brandHubToggle) brandHubToggle.addEventListener('click', () => toggleBrandHub());
    if (closeBrandHub) closeBrandHub.addEventListener('click', () => toggleBrandHub(false));
    if (resetBrandHub) resetBrandHub.addEventListener('click', () => { clearSelectedBrand(); toggleBrandHub(true); });

    if (dateHubToggle) dateHubToggle.addEventListener('click', () => toggleDateHub());
    if (timeHubToggle) timeHubToggle.addEventListener('click', () => toggleTimeHub());

    datePicks.forEach(btn => {
        btn.addEventListener('click', () => {
            const val = btn.getAttribute('data-date-value');
            dateInput.value = val;
            dateHubLabel.textContent = val;
            dateHubLabel.classList.remove('text-slate-400');
            dateHubLabel.classList.add('text-slate-900');
            datePicks.forEach(b => b.classList.remove('bg-orange-50', 'border-orange-100'));
            btn.classList.add('bg-orange-50', 'border-orange-100');
            toggleDateHub(false);
        });
    });

    timePicks.forEach(btn => {
        btn.addEventListener('click', () => {
            const val = btn.getAttribute('data-time-value');
            timeInput.value = val;
            timeHubLabel.textContent = val;
            timeHubLabel.classList.remove('text-slate-400');
            timeHubLabel.classList.add('text-slate-900');
            timePicks.forEach(b => b.classList.remove('bg-orange-50', 'border-orange-100'));
            btn.classList.add('bg-orange-50', 'border-orange-100');
            toggleTimeHub(false);
        });
    });

    if (yearHubToggle) yearHubToggle.addEventListener('click', () => toggleYearHub());
    yearPicks.forEach(btn => {
        btn.addEventListener('click', () => {
            const val = btn.getAttribute('data-year-value');
            yearInput.value = val;
            yearHubLabel.textContent = val;
            yearHubLabel.classList.remove('text-slate-400');
            yearHubLabel.classList.add('text-slate-900');
            yearPicks.forEach(b => {
                b.classList.remove('bg-orange-50', 'border-orange-100', 'text-slate-900');
                b.classList.add('text-slate-700');
            });
            btn.classList.add('bg-orange-50', 'border-orange-100', 'text-slate-900');
            btn.classList.remove('text-slate-700');
            toggleYearHub(false);
        });
    });

    if (gccHubToggle) gccHubToggle.addEventListener('click', () => toggleGccHub());
    gccPicks.forEach(btn => {
        btn.addEventListener('click', () => {
            gccInput.value = btn.getAttribute('data-gcc-value');
            gccHubLabel.textContent = btn.getAttribute('data-gcc-label');
            gccHubLabel.classList.add('text-slate-900');
            gccPicks.forEach(b => b.classList.remove('btn-active-orange'));
            btn.classList.add('btn-active-orange');
            toggleGccHub(false);
        });
    });

    if (bodyHubToggle) bodyHubToggle.addEventListener('click', () => toggleBodyHub());
    bodyPicks.forEach(btn => {
        btn.addEventListener('click', () => {
            bodyInput.value = btn.getAttribute('data-body-value');
            bodyHubLabel.textContent = bodyInput.value;
            bodyHubLabel.classList.add('text-slate-900');
            toggleBodyHub(false);
        });
    });

    if (engineHubToggle) engineHubToggle.addEventListener('click', () => toggleEngineHub());
    enginePicks.forEach(btn => {
        btn.addEventListener('click', () => {
            engineInput.value = btn.getAttribute('data-engine-value');
            engineHubLabel.textContent = engineInput.value;
            engineHubLabel.classList.add('text-slate-900');
            toggleEngineHub(false);
        });
    });

    if (mileageHubToggle) mileageHubToggle.addEventListener('click', () => toggleMileageHub());
    mileagePicks.forEach(btn => {
        btn.addEventListener('click', () => {
            mileageInput.value = btn.getAttribute('data-mileage-value');
            mileageHubLabel.textContent = mileageInput.value + ' KM';
            mileageHubLabel.classList.add('text-slate-900');
            toggleMileageHub(false);
        });
    });

    trimPicks.forEach(btn => { btn.addEventListener('click', () => { trimInput.value = btn.getAttribute('data-trim-value'); trimPicks.forEach(b => b.classList.remove('btn-active-orange')); btn.classList.add('btn-active-orange'); }); });
    paintPicks.forEach(btn => { btn.addEventListener('click', () => { paintInput.value = btn.getAttribute('data-paint-value'); paintPicks.forEach(b => b.classList.remove('btn-active-orange')); btn.classList.add('btn-active-orange'); }); });
    conditionPicks.forEach(btn => {
        btn.addEventListener('click', () => {
            conditionInput.value = btn.getAttribute('data-condition-value');
            conditionPicks.forEach(b => {
                b.classList.remove('btn-active-orange', 'border-[#FF6900]', 'text-slate-900');
                b.classList.add('text-slate-400', 'border-slate-100');
            });
            btn.classList.add('btn-active-orange', 'border-[#FF6900]', 'text-slate-900');
            btn.classList.remove('text-slate-400', 'border-slate-100');
        });
    });

    if (modelHubToggle) modelHubToggle.addEventListener('click', () => toggleModelHub());
    if (closeModelHub)  closeModelHub.addEventListener('click', () => toggleModelHub(false));

    if (modelHubSearch) {
        modelHubSearch.addEventListener('input', (e) => {
            const q = e.target.value.toLowerCase().trim();
            const modelBtns = Array.from(modelListContainer?.querySelectorAll('[data-model-value]') || []);
            modelBtns.forEach(btn => {
                const name = (btn.getAttribute('data-model-value') || '').toLowerCase();
                btn.style.display = name.includes(q) ? 'flex' : 'none';
            });
        });
    }

    if (brandHubSearch) {
        brandHubSearch.addEventListener('input', (e) => {
            const q = e.target.value.toLowerCase().trim();
            brandHubOptions.forEach(opt => {
                const name = (opt.getAttribute('data-brand-hub-value') || '').toLowerCase();
                opt.style.display = name.includes(q) ? 'flex' : 'none';
            });
        });
    }

    brandHubOptions.forEach(btn => {
        btn.addEventListener('click', () => {
            const value = btn.getAttribute('data-brand-hub-value') || '';
            const logo = btn.getAttribute('data-brand-hub-logo') || '';
            const models = parseModels(btn.getAttribute('data-brand-models'));
            setSelectedBrand(value, logo, false, models);
            toggleBrandHub(false);
        });
    });

    btnNexts.forEach(btn => {
        btn.addEventListener('click', () => {
            if (validateStep(currentIdx)) {
                currentIdx++;
                updateUI();
            }
        });
    });

    btnBacks.forEach(btn => {
        btn.addEventListener('click', () => {
            currentIdx = Math.max(0, currentIdx - 1);
            updateUI();
        });
    });

    popularBrandPicks.forEach(btn => {
        btn.addEventListener('click', () => {
            const value = btn.getAttribute('data-brand-pick') || '';
            const logo = btn.querySelector('img')?.src || '';
            const models = parseModels(btn.getAttribute('data-brand-models'));
            setSelectedBrand(value, logo, false, models);
            if (brandHubDrawer) brandHubDrawer.classList.add('hidden');
        });
    });

    document.addEventListener('click', (e) => {
        if (brandHubDrawer && brandHubToggle && !brandHubDrawer.contains(e.target) && !brandHubToggle.contains(e.target)) toggleBrandHub(false);
        if (modelHubDrawer && modelHubToggle && !modelHubDrawer.contains(e.target) && !modelHubToggle.contains(e.target)) toggleModelHub(false);
        if (gccHubDrawer && gccHubToggle && !gccHubDrawer.contains(e.target) && !gccHubToggle.contains(e.target)) toggleGccHub(false);
        if (yearHubDrawer && yearHubToggle && !yearHubDrawer.contains(e.target) && !yearHubToggle.contains(e.target)) toggleYearHub(false);
        if (dateHubDrawer && dateHubToggle && !dateHubDrawer.contains(e.target) && !dateHubToggle.contains(e.target)) toggleDateHub(false);
        if (timeHubDrawer && timeHubToggle && !timeHubDrawer.contains(e.target) && !timeHubToggle.contains(e.target)) toggleTimeHub(false);
    });

    wizard.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (!validateStep(2)) return;
        if (!btnSubmit) return;

        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<span class="animate-pulse">Syncing Node...</span>';

        try {
            const formData = new FormData(wizard);
            
            // Mileage Mapping
            const rawMileage = formData.get('mileage');
            const mileageMap = { '0 - 20k': 20000, '20k - 50k': 50000, '50k - 100k': 100000, '100k - 150k': 150000, '150k - 200k': 200000, 'Over 200k': 250000, 'Unknown': 0 };
            if (mileageMap[rawMileage] !== undefined) formData.set('mileage', mileageMap[rawMileage]);
            else formData.set('mileage', parseInt(rawMileage?.replace(/[^0-9]/g, '')) || 0);

            // Appointment Parsing
            const rawDate = formData.get('inspection_date');
            const rawTime = formData.get('inspection_time');
            if (rawDate) {
                const dateObj = new Date(rawDate);
                if (!isNaN(dateObj.getTime())) {
                    formData.set('inspection_date', dateObj.toISOString().split('T')[0]);
                }
            }

            const res = await fetch(wizard.action, {
                method: 'POST',
                body: formData,
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });

            if (!res.ok) {
                const errorData = await res.json().catch(() => ({}));
                throw new Error(errorData.message || 'Submission failed');
            }

            const container = wizard.closest('.sell-wizard-card') || wizard.parentElement;
            if (container) {
                container.innerHTML = `
                    <div class="py-12 text-center animate-in zoom-in duration-500">
                        <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl shadow-emerald-500/10">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black text-slate-800 mb-2">Request Submitted</h3>
                        <p class="text-slate-500 text-sm">Your data was pushed to the Elite CRM Segment. An operator will respond shortly.</p>
                        <button onclick="window.location.reload()" class="mt-8 text-[0.65rem] font-bold uppercase tracking-widest text-[#ff6900] border-b-2 border-orange-100 pb-1">Submit New Lead</button>
                    </div>
                `;
            }
        } catch (err) {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = 'Retry Syncing';
            window.BazarToast.error('Submission failed: ' + err.message);
        }
    });

    // ── Leaflet Support ──
    function initHomeLeaflet() {
        if (window._homeLeafletMap) {
            setTimeout(() => window._homeLeafletMap.invalidateSize(), 80);
            return;
        }

        if (mapProvider === 'google' && window.google?.maps?.places) {
            const ai = document.getElementById('homeAddressSearch');
            if (ai) new window.google.maps.places.Autocomplete(ai, { componentRestrictions: { country: 'ae' }, fields: ['address_components', 'geometry', 'name'] });
            return;
        }

        if (!window.L) return;
        const mapEl = document.getElementById('leafletHomeMap');
        if (!mapEl) return;

        const map = window.L.map('leafletHomeMap').setView(branchCoords, 14);
        window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);

        const orangeIcon = window.L.divIcon({
            html: `<div style="width:36px;height:36px;background:#FF6900;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 4px 15px rgba(255,105,0,0.4);"></div>`,
            iconSize: [36, 36], iconAnchor: [18, 36], className: ''
        });

        const marker = window.L.marker(branchCoords, { draggable: true, icon: orangeIcon }).addTo(map);
        window._homeLeafletMap = map;
        window._homeLeafletMarker = marker;

        map.on('click', (e) => { marker.setLatLng(e.latlng); reverseGeocodeHome(e.latlng.lat, e.latlng.lng); });
        marker.on('dragend', (e) => { const pos = e.target.getLatLng(); reverseGeocodeHome(pos.lat, pos.lng); });

        const searchInput = document.getElementById('homeAddressSearch');
        if (searchInput) {
            const osmResults = document.createElement('div');
            osmResults.id = 'homeOsmResults';
            osmResults.style.cssText = 'position:absolute;top:100%;left:0;right:0;z-index:1200;background:#fff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 20px 40px rgba(0,0,0,0.12);margin-top:4px;display:none;overflow:hidden;';
            const wrapper = searchInput.closest('.flex-1.relative') || searchInput.parentElement;
            wrapper.style.position = 'relative'; 
            wrapper.appendChild(osmResults);

            let timer;
            searchInput.addEventListener('input', () => {
                clearTimeout(timer);
                const q = searchInput.value.trim();
                if (q.length < 3) { osmResults.style.display = 'none'; return; }
                timer = setTimeout(() => {
                    fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=6&q=${encodeURIComponent(q)}`, { headers: { 'User-Agent': 'MotorBazar' } })
                    .then(r => r.json())
                    .then(items => renderHomeOsmResults(items, map, marker, osmResults, searchInput))
                    .catch(() => osmResults.style.display = 'none');
                }, 500);
            });
        }
    }

    function renderHomeOsmResults(data, map, marker, resultsEl, inputEl) {
        if (!data?.length) { resultsEl.style.display = 'none'; return; }
        resultsEl.innerHTML = '';
        data.forEach(item => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'w-full p-3 text-left hover:bg-[#fef3ec] flex items-center gap-2 border-b border-slate-50';
            btn.innerHTML = `<span class="text-xs font-semibold text-slate-700 truncate">${item.display_name}</span>`;
            btn.onclick = () => {
                const lat = parseFloat(item.lat), lon = parseFloat(item.lon);
                map.setView([lat, lon], 16); marker.setLatLng([lat, lon]);
                inputEl.value = item.display_name; resultsEl.style.display = 'none';
            };
            resultsEl.appendChild(btn);
        });
        resultsEl.style.display = 'block';
    }

    function reverseGeocodeHome(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`, { headers: { 'User-Agent': 'MotorBazar' } })
        .then(r => r.json()).then(data => {
            const input = document.getElementById('homeAddressSearch');
            if (input) input.value = data.display_name || `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
        });
    }

    function initDateAndTimePickers() {
        // --- CUSTOM DATE PICKER ENGINE ---
        (function() {
            const toggle     = document.getElementById('datePickerToggle');
            const drawer     = document.getElementById('datePickerDrawer');
            const label      = document.getElementById('datePickerLabel');
            const hiddenVal  = document.getElementById('inspectionDateVal');
            const grid       = document.getElementById('calDaysGrid');
            const monthLabel = document.getElementById('calMonthYear');
            const btnPrev    = document.getElementById('calPrev');
            const btnNext    = document.getElementById('calNext');
            if (!toggle || !drawer) return;

            const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
            const today  = new Date(); today.setHours(0,0,0,0);
            let viewDate = new Date(today.getFullYear(), today.getMonth(), 1);
            let selected = null;

            function renderCalendar() {
                const yr  = viewDate.getFullYear();
                const mo  = viewDate.getMonth();
                monthLabel.textContent = MONTHS[mo] + ' ' + yr;
                grid.innerHTML = '';

                const firstDay = new Date(yr, mo, 1).getDay();
                const daysInMo = new Date(yr, mo + 1, 0).getDate();

                for (let i = 0; i < firstDay; i++) {
                    const blank = document.createElement('div');
                    grid.appendChild(blank);
                }

                for (let d = 1; d <= daysInMo; d++) {
                    const date = new Date(yr, mo, d);
                    const btn  = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = d;

                    const isPast    = date < today;
                    const isToday   = date.getTime() === today.getTime();
                    const isSel     = selected && date.getTime() === selected.getTime();

                    btn.className = 'w-full aspect-square flex items-center justify-center rounded-lg text-[0.65rem] font-bold transition-all ';
                    if (isPast) {
                        btn.className += 'text-slate-200 cursor-not-allowed';
                        btn.disabled = true;
                    } else if (isSel) {
                        btn.className += 'bg-[#FF6900] text-white shadow-md shadow-orange-200 font-black';
                    } else if (isToday) {
                        btn.className += 'ring-2 ring-[#FF6900]/40 text-[#FF6900] font-black hover:bg-orange-50';
                    } else {
                        btn.className += 'text-slate-600 hover:bg-orange-50 hover:text-[#FF6900]';
                    }

                    if (!isPast) {
                        btn.addEventListener('click', () => {
                            selected = date;
                            const iso = `${yr}-${String(mo+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
                            if (hiddenVal) hiddenVal.value = iso;
                            if (dateInput) dateInput.value = iso;
                            label.textContent = date.toLocaleDateString('en-US', { weekday:'short', month:'short', day:'numeric' });
                            label.classList.replace('text-slate-300','text-slate-800');
                            toggle.classList.add('border-[#FF6900]');
                            toggle.classList.remove('border-slate-100');
                            drawer.classList.add('hidden');
                            renderCalendar();
                        });
                    }
                    grid.appendChild(btn);
                }
            }

            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                drawer.classList.toggle('hidden');
                document.getElementById('timePickerDrawer')?.classList.add('hidden');
                renderCalendar();
            });

            btnPrev?.addEventListener('click', (e) => { viewDate.setMonth(viewDate.getMonth() - 1); renderCalendar(); });
            btnNext?.addEventListener('click', (e) => { viewDate.setMonth(viewDate.getMonth() + 1); renderCalendar(); });

            document.addEventListener('click', (e) => {
                if (!document.getElementById('datePicker')?.contains(e.target)) {
                    drawer.classList.add('hidden');
                }
            });

            renderCalendar();
        })();

        // --- CUSTOM TIME DRUM PICKER ENGINE ---
        (function() {
            const toggle    = document.getElementById('timePickerToggle');
            const drawer    = document.getElementById('timePickerDrawer');
            const label     = document.getElementById('timePickerLabel');
            const hiddenVal = document.getElementById('inspectionTimeVal');
            if (!toggle || !drawer) return;

            const HOURS   = [9,10,11,12,1,2,3,4,5];
            const MINUTES = ['00','15','30','45'];
            let hrIdx  = 0;   
            let minIdx = 0;   
            let isPM   = false;

            const hrCur     = document.getElementById('hrCurrent');
            const minCur    = document.getElementById('minCurrent');
            const amBtn     = document.getElementById('amToggle');
            const pmBtn     = document.getElementById('pmToggle');
            const confirmBtn= document.getElementById('timeConfirm');

            function renderDrums() {
                const hCur  = HOURS[hrIdx];
                const mCur  = MINUTES[minIdx];
                if(hrCur)  hrCur.textContent  = String(hCur).padStart(2,'0');
                if(minCur) minCur.textContent  = mCur;
                
                const hPrev = HOURS[(hrIdx - 1 + HOURS.length) % HOURS.length];
                const hNxt  = HOURS[(hrIdx + 1) % HOURS.length];
                const mPrev = MINUTES[(minIdx - 1 + MINUTES.length) % MINUTES.length];
                const mNxt  = MINUTES[(minIdx + 1) % MINUTES.length];
                
                const hrPrev = document.getElementById('hrPrev');
                const hrNxt = document.getElementById('hrNext');
                const minPrev = document.getElementById('minPrev');
                const minNxt = document.getElementById('minNext');
                
                if(hrPrev) hrPrev.textContent = String(hPrev).padStart(2,'0');
                if(hrNxt) hrNxt.textContent = String(hNxt).padStart(2,'0');
                if(minPrev) minPrev.textContent = mPrev;
                if(minNxt) minNxt.textContent = mNxt;
            }

            function buildTimeStr() {
                const h = HOURS[hrIdx], m = MINUTES[minIdx], per = isPM ? 'PM' : 'AM';
                return String(h).padStart(2,'0') + ':' + m + ' ' + per;
            }

            function setAMPM(pm) {
                isPM = pm;
                if (amBtn) amBtn.className = !pm ? 'px-2.5 py-1 rounded-lg text-[0.55rem] font-black uppercase tracking-wider bg-[#FF6900] text-white transition-all' : 'px-2.5 py-1 rounded-lg text-[0.55rem] font-black uppercase tracking-wider bg-slate-100 text-slate-400 transition-all';
                if (pmBtn) pmBtn.className = pm ? 'px-2.5 py-1 rounded-lg text-[0.55rem] font-black uppercase tracking-wider bg-[#FF6900] text-white transition-all' : 'px-2.5 py-1 rounded-lg text-[0.55rem] font-black uppercase tracking-wider bg-slate-100 text-slate-400 transition-all';
            }

            document.getElementById('hrUp')?.addEventListener('click', (e) => { e.stopPropagation(); hrIdx = (hrIdx - 1 + HOURS.length) % HOURS.length; renderDrums(); });
            document.getElementById('hrDown')?.addEventListener('click', (e) => { e.stopPropagation(); hrIdx = (hrIdx + 1) % HOURS.length; renderDrums(); });
            document.getElementById('minUp')?.addEventListener('click', (e) => { e.stopPropagation(); minIdx = (minIdx - 1 + MINUTES.length) % MINUTES.length; renderDrums(); });
            document.getElementById('minDown')?.addEventListener('click', (e) => { e.stopPropagation(); minIdx = (minIdx + 1) % MINUTES.length; renderDrums(); });
            amBtn?.addEventListener('click', (e) => { e.stopPropagation(); setAMPM(false); });
            pmBtn?.addEventListener('click', (e) => { e.stopPropagation(); setAMPM(true); });

            confirmBtn?.addEventListener('click', (e) => {
                const timeStr = buildTimeStr();
                if (hiddenVal) hiddenVal.value = timeStr;
                if (timeInput) timeInput.value = timeStr;
                label.textContent = timeStr;
                label.classList.replace('text-slate-300','text-slate-800');
                toggle.classList.add('border-[#FF6900]');
                toggle.classList.remove('border-slate-100');
                drawer.classList.add('hidden');
            });

            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                drawer.classList.toggle('hidden');
                document.getElementById('datePickerDrawer')?.classList.add('hidden');
                renderDrums();
            });

            document.addEventListener('click', (e) => {
                if (!document.getElementById('timePicker')?.contains(e.target)) drawer.classList.add('hidden');
            });

            renderDrums();
        })();
    }

    // ── Initial State ──
    const currentBrand = makeSelect?.value || '';
    if (currentBrand) {
        const btn = brandHubOptions.find(b => normalizeMake(b.getAttribute('data-brand-hub-value')) === normalizeMake(currentBrand));
        setSelectedBrand(currentBrand, btn?.getAttribute('data-brand-hub-logo') || '', true, parseModels(btn?.getAttribute('data-brand-models')));
        if (modelInput?.value) setSelectedModel(modelInput.value);
    } else {
        populateModels([]);
    }
    initDateAndTimePickers();
    updateUI();
}
