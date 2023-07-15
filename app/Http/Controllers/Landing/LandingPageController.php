<?php

namespace App\Http\Controllers\Landing;

use App\User;
use App\Admin\Models\Banner;
use Illuminate\Http\Request;
use App\Admin\Models\Testimonio;
use App\Models\Suscripcion\Plan;
use App\Http\Controllers\Controller;
use App\Admin\Models\Cliente\Cliente;
use App\Admin\Models\ContCaracteristica;
use App\Notifications\Landing\ContactForm;
use App\Admin\Models\TestimonioContabilidad;
use App\Http\Requests\Landing\ContactoFormRequest;

class LandingPageController extends Controller
{
  public function index()
  {
    $planes = Plan::getFormatLanding();
    $clientesGroup = array_chunk(Cliente::allWithLogoPath(true),10);
    $testimonios = Testimonio::allWithLogoPath();
    $banners = Banner::allWithLogoPath();

    // dd( $banners );
    // $testimonios = [];
    return view('landing.index', compact('planes', 'banners', 'clientesGroup', 'testimonios'));
  }

  public function contabilidad()
  {
    $caracteristicas =  ContCaracteristica::all();
    $testimonios_group =  TestimonioContabilidad::all()->chunk(2);
    $url_testimonio = get_setting('video_presentacion_contabilidad');
    $link_whatapp_contact_contact = get_setting('link_whatapp_contact_contact');
    return view('landing.contabilidad', compact('caracteristicas', 'testimonios_group', 'url_testimonio', 'link_whatapp_contact_contact'));
  }


  public function contact()
  {
    return view('landing.contact');
  }

  public function send(ContactoFormRequest $request)
  {
    User::getAdmin()->notify(new ContactForm($request->all()));
    return redirect()
      ->back()
      ->with('message','Formulario enviado satisfactoriamente, te responderemos a la brevedad');
  }

  public function show($id)
  {
  }

  public function edit($id)
  {
  }

  public function update(Request $request, $id)
  {
  }

  public function destroy($id)
  {
  }
}
