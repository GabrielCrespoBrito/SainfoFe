<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class ViewHelper
{
  public $librerias_path;
  public $links;
  public $secure;
  public $enviroment;

  const LIBRARYS = ['datatable', 'popover', 'datepicker', 'select2', 'download', 'froala_editor', 'ckeditor', 'ckeditor5'];
  public function __construct()
  {

    $this->enviroment = config('app.env');
    $this->secure = $this->enviroment == 'production';

    $this->librerias_path = [

      // javascript
      'js' => [

        'datatable' => [
          asset_force_https('plugins/datatable/jquery.dataTables.min.js', $this->secure),
          asset_force_https('plugins/datatable/dataTables.bootstrap.js', $this->secure)
        ],

        'popover' => [
          asset_force_https('plugins/popover/script.js', $this->secure),
        ],


        'datepicker' => [
          asset_force_https('plugins/bootstrap-daterangepicker/moment.min.js', $this->secure),
          asset_force_https('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js', $this->secure)
        ],

        'select2' => [asset_force_https('plugins/select2/select2.js', $this->secure)],
        'download' => [asset_force_https('plugins/download/download.js', $this->secure)],
        'ckeditor' => [asset_force_https('plugins/ckeditor/ckeditor.js', $this->secure)],
        'ckeditor5' => [asset_force_https('plugins/ckeditor5/ckeditor.js', $this->secure)],

        'froala_editor' => [
          "https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js",
          "https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js",
          asset_force_https("plugins/froala_editor/js/froala_editor.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/align.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/char_counter.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/code_beautifier.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/code_view.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/colors.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/draggable.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/emoticons.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/entities.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/file.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/font_size.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/font_family.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/fullscreen.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/image.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/image_manager.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/line_breaker.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/inline_style.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/link.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/lists.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/paragraph_format.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/paragraph_style.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/quick_insert.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/quote.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/table.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/save.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/url.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/video.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/help.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/print.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/third_party/spell_checker.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/special_characters.min.js",  $this->secure),
          asset_force_https("plugins/froala_editor/js/plugins/word_paste.min.js",  $this->secure),
        ]
      ],

      // css  	
      'css' => [
        'datatable'  => [asset_force_https('plugins/datatable/dataTables.bootstrap.css', $this->secure)],
        'datepicker' => [asset_force_https('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css', $this->secure)],
        'select2'    => [asset_force_https('plugins/select2/select2.css', $this->secure)],
        'popover'    => [asset_force_https('plugins/popover/style.css', $this->secure)],
        'ckeditor5' => [asset_force_https('plugins/ckeditor5/style.css', $this->secure)],
        'froala_editor' => [
          asset_force_https("plugins/froala_editor/css/froala_editor.css", $this->secure),
          asset_force_https("plugins/froala_editor/css/froala_style.css", $this->secure),
          asset_force_https("plugins/froala_editor/css/plugins/code_view.css", $this->secure),
          asset_force_https("plugins/froala_editor/css/plugins/draggable.css", $this->secure),
          asset_force_https("plugins/froala_editor/css/plugins/colors.css", $this->secure),
          asset_force_https("plugins/froala_editor/css/plugins/emoticons.css", $this->secure),
          asset_force_https("plugins/froala_editor/css/plugins/image_manager.css", $this->secure),
          asset_force_https("plugins/froala_editor/css/plugins/image.css", $this->secure),
          asset_force_https("plugins/froala_editor/css/plugins/line_breaker.css", $this->secure),
          asset_force_https("plugins/froala_editor/css/plugins/table.css", $this->secure),
          asset_force_https("plugins/froala_editor/css/plugins/char_counter.css", $this->secure),
          asset_force_https("plugins/froala_editor/css/plugins/video.css", $this->secure),
          asset_force_https("plugins/froala_editor/css/plugins/fullscreen.css", $this->secure),
          asset_force_https("plugins/froala_editor/css/plugins/file.css", $this->secure),
          asset_force_https("plugins/froala_editor/css/plugins/quick_insert.css", $this->secure),
          asset_force_https("plugins/froala_editor/css/plugins/help.css", $this->secure),
          asset_force_https("plugins/froala_editor/css/third_party/spell_checker.css", $this->secure),
          asset_force_https("plugins/froala_editor/css/plugins/special_characters.css", $this->secure),
          "https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.css",
        ],
      ]
    ];

    $this->links = [
      'js' => "<script type='text/javascript' src='[direccion]'></script>",
      'js_module' => "<script type='module' src='[direccion]'></script>",
      'css' => "<link rel='stylesheet' href='[direccion]'/>",
    ];
  }

  public function getPathLibrerias($libreria)
  {
    if (!in_array($libreria, self::LIBRARYS)) {
      throw new \Exception("La libreria ($libreria) no se encuentra agregada", 1);
    }

    $dirs = [
      'js' =>  ['has' => false, 'paths' => ""],
      'css' => ['has' => false, 'paths' => ""],
    ];

    $libreria_paths = [];

    // Ver si esta libreria tiene archivos css o js 
    if (isset($this->librerias_path['js'][$libreria])) {
      array_push($libreria_paths, $this->librerias_path['js'][$libreria]);
    }
    if (isset($this->librerias_path['css'][$libreria])) {
      array_push($libreria_paths, $this->librerias_path['css'][$libreria]);
    }


    $libreria_paths = collect($libreria_paths)->collapse()->all();

    foreach ($libreria_paths as $path) {
      if (strpos($path, '.css') !== false) {
        $dirs['css']['paths'] .= str_replace("[direccion]", $path, $this->links['css']);;
        $dirs['css']['has'] = true;
      } else {
        $dirs['js']['paths'] .= str_replace("[direccion]", $path, $this->links['js']);;
        $dirs['js']['has'] = true;
      }
    }

    return $dirs;
  }

  public function getPathJs($script, $is_module = false)
  {


    $mix = false;

    if (strpos($script, "_mod") !== false) {
      $script = str_replace("_mod", "", $script);
      $is_module = true;
    }

    // Comprobar si tiene _mix, esto indicara que es una ruta mix
    if (strpos($script, "mix") !== false) {
      $script = str_replace("_mix", "", $script);
      $mix = true;
    }

    $direccion = public_path("js/" . $script);

    if (!file_exists($direccion)) {
      throw new \Exception("La no existe ningun archivo en la direcciòn ($direccion)", 1);
    }

    $direccion = asset_force_https("js/" . $script, $this->secure, $mix);

    $link = $is_module ? $this->links['js_module'] : $this->links['js'];

    return str_replace("[direccion]", $direccion, $link);
  }
}
