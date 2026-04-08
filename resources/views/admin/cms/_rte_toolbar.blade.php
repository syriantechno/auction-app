@php $rteId = 'rte_'.uniqid(); @endphp
<div style="display:flex;align-items:center;gap:2px;padding:5px 8px;background:#f8fafc;border-bottom:1px solid #e2e8f0;border-radius:6px 6px 0 0;flex-wrap:nowrap;">
    <button type="button" onclick="rteCmd(this,'bold')" title="Bold"
        style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:26px;border-radius:4px;border:none;background:none;font-size:.78rem;font-weight:900;color:#475569;cursor:pointer;flex-shrink:0;">B</button>
    <button type="button" onclick="rteCmd(this,'italic')" title="Italic"
        style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:26px;border-radius:4px;border:none;background:none;font-size:.78rem;font-style:italic;font-weight:700;color:#475569;cursor:pointer;flex-shrink:0;">I</button>
    <button type="button" onclick="rteCmd(this,'underline')" title="Underline"
        style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:26px;border-radius:4px;border:none;background:none;font-size:.78rem;font-weight:700;text-decoration:underline;color:#475569;cursor:pointer;flex-shrink:0;">U</button>
    <button type="button" onclick="rteCmd(this,'strikeThrough')" title="Strikethrough"
        style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:26px;border-radius:4px;border:none;background:none;font-size:.78rem;font-weight:700;text-decoration:line-through;color:#475569;cursor:pointer;flex-shrink:0;">S</button>

    <div style="width:1px;height:16px;background:#d1d5db;margin:0 4px;flex-shrink:0;"></div>

    <select onchange="rteCmd(this,'fontSize',this.value);this.value=''" title="Font Size"
        style="height:26px;border:1px solid #e2e8f0;border-radius:4px;background:#fff;font-size:.68rem;font-weight:600;color:#475569;padding:0 4px;cursor:pointer;outline:none;flex-shrink:0;">
        <option value="">Size</option>
        <option value="1">XS</option>
        <option value="2">S</option>
        <option value="3">M</option>
        <option value="4">L</option>
        <option value="5">XL</option>
        <option value="6">2XL</option>
        <option value="7">3XL</option>
    </select>

    <div style="width:1px;height:16px;background:#d1d5db;margin:0 4px;flex-shrink:0;"></div>

    <label title="Text Color" onmousedown="rteSaveSelection()" style="position:relative;width:26px;height:26px;flex-shrink:0;cursor:pointer;">
        <div id="{{ $rteId }}_fg" style="width:26px;height:26px;border-radius:4px;border:1.5px solid #e2e8f0;background:#031629;pointer-events:none;"></div>
        <input type="color" value="#031629"
            style="position:absolute;inset:0;opacity:0;width:100%;height:100%;cursor:pointer;"
            oninput="document.getElementById('{{ $rteId }}_fg').style.background=this.value"
            onchange="rteApplyColor(this,'color')">
    </label>

    <label title="Highlight" onmousedown="rteSaveSelection()" style="position:relative;width:26px;height:26px;flex-shrink:0;cursor:pointer;">
        <div id="{{ $rteId }}_bg" style="width:26px;height:26px;border-radius:4px;border:1.5px solid #e2e8f0;background:#fef08a;pointer-events:none;"></div>
        <input type="color" value="#fef08a"
            style="position:absolute;inset:0;opacity:0;width:100%;height:100%;cursor:pointer;"
            oninput="document.getElementById('{{ $rteId }}_bg').style.background=this.value"
            onchange="rteApplyColor(this,'background')">
    </label>

    <div style="width:1px;height:16px;background:#d1d5db;margin:0 4px;flex-shrink:0;"></div>

    <button type="button" onclick="rteCmd(this,'removeFormat')" title="Clear formatting"
        style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:26px;border-radius:4px;border:none;background:none;font-size:.65rem;color:#94a3b8;cursor:pointer;flex-shrink:0;">✕</button>
</div>
