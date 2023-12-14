@php
$class_name = $class_name ?? "";
$table_class = $table_class ?? "";
@endphp

<div class="{{ $class_name }}">
  <table class="{{ $table_class }}" width="100%">
      <tbody>
        <tr style="text-align: left;">
          <td class="" width="50%">
            <div style="padding-top:10px;text-align:center">
              <div> _________________ </div>
              <div> Emisor </div>
            </div>
          </td>
          <td class="" width="50%">
            <div style="padding-top:10px;  text-align:center">
              <div> _________________ </div>
              <div> Cliente </div>
            </div>
          </td>
        </tr>
    <tbody>
  </table>

</div>