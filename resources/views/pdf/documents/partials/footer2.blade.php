@php
$class_name = $class_name ?? '';
@endphp

<div class="footer {{ $class_name }}">
  <table class="width-100">

    <!-- Info Anexo -->
    <tr>
      <td colspan="2">
        {{ $info_anexo }}
      </td>
    </tr>
    <!-- /Info Anexo -->


    <!-- Row 2 -->
    <tr>
      <!-- Column1 -->
      <td class="width-50">
        {{ $column_1 }}
      </td>
      <!-- /Column1 -->

      <!-- Column2 -->
      <td class="width-50">
        {{ $column_2 }}
      </td>
      <!-- /Column2 -->
    </tr>
    <!-- /Row 2 -->


    <!-- Row 2 -->
    <tr>
      <!-- Column3 -->
      <td class="width-50">
        {{ $column_3 }}
      </td>
      <!-- /Column1 -->

      <!-- Column4 -->
      <td class="width-50">
        {{ $column_4 }}
      </td>
      <!-- /Column2 -->
    </tr>
    <!-- /Row 2-->



  </table>
</div>