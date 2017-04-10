<!-- Smart select item -->
<!-- Additional "smart-select" class -->
<a href="#" class="item-link smart-select"
  data-page-title="{{ $picker_title }}" data-back-text="Cerrar"
  data-searchbar-placeholder="{{ ($placeholder) ? $placeholder : 'Buscar...' }}"
  data-picker-height="400px">
  <!-- select -->
  <select {{ (isset($id)) ? "id=$id" : '' }} name="{{ $select_name }}" 
    {{ ($is_multiple) ? 'multiple' : '' }}>
    @foreach($options as $key => $val)
      <option value="{{ $key }}">{{ $val }}</option>
    @endforeach
  </select>
  <div class="item-content">
    <div class="item-inner">
      <!-- Select label -->
      <div class="item-title">{{ $label }}</div>
    </div>
  </div>
</a>
