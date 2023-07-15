<?php

namespace Helpers;

use mikehaertl\wkhtmlto\Pdf;

class PDFHelper
{
	// Packetes
	const DOMPDF = "DOM";
	const WKPDF = "WKPDF";

	// Opciones por defecto para los packetes
	public $defaults = [
		
		// DOMPDF
		self::DOMPDF => [],

		// WKPDF
		self::WKPDF  => [

			// Command options
			'commandOptions' => [
		  'useExec' => true,
		  'escapeArgs' => false,
		  'locale' => 'es_ES.UTF-8',
		  'procOptions' => [
				'bypass_shell' => true,
				'suppress_errors' => true ],
			],
			// --------------------

			// Global Options
			'globalOptions' => [ 
				'no-outline', 
				'page-size' => 'Letter' ,
				 'orientation' => 'landscape' 
			]
			// --------------------

		],

	];

	// Paquete a utilizar
	public $package;
	// Vista que se va a renderizar
	public $page;
	// Parametros
	public $options;


	public function __construct( $package , $page , Array $options = [] )
	{
		$this->page = $page;
		$this->package = $package;
		$this->options = $options;
	}

	public function isDOMPDF(){
		return $this->package == self::DOMPDF;		
	}

	public function isWKPDF(){
		return ! $this->isDOMPDF;		
	}


	public function replaceOption( &$options_default, $options_new )
	{		
		// $option = array_merge_recursive( $options_default, $options_new );
		// $options_default = $option;

		// foreach ($options_new as $key => $value) {

		// 	if( array_key_exists( $key, $options_default ) ){

		// 		if( is_array($options_default[$key]) ){
					
		// 			if( ! is_array( $value ) ){
		// 				dd("Error");
		// 			}

		// 			$this->replaceOption( $options_default[$key] , $value );
		// 		}
		// 		else {
		// 			$options_default[$key] = $value;
		// 		}
		// 	}
		// 	else {
		// 		$options_default[$key] = $value;
		// 	}
		// }
	}

	public function processOptions(){

		if( $this->isWKPDF() ){
			if( array_key_exists("commandOptions", $this->options ) ){
				$this->replaceOption( $this->defaults[self::WKPDF]['commandOptions'] , $this->options['commandOptions'] );
			}
			if( array_key_exists("globalOptions", $this->options ) ){
				$this->replaceOption( $this->defaults[self::WKPDF]['globalOptions'] , $this->options['globalOptions'] );				
			}			
		}
	}

	public function stream(){

		if( $this->isWKPDF() ){			
			return $this->streamWKPDF();
		}

		else {
			return $this->streamDOMPDF();			
		}
	}

	public function initLibrary(){
		if( $this->isWKPDF() ){		
			$pdf = newPdf($defaults[self::WKPDF]['commandOptions']);
			$pdf->setOptions($defaults[self::WKPDF]['globalOptions']);
		}

		else {
		}		
	}

	public function streamWKPDF(){

		$pdf = $this->initLibrary();
		return $pdf->addPage( $this->page );
	}

	public function streamDOMPDF(){

	}


}

/*

mi novio 21f 30m es muy raro en el sexo
Disfruto teniendo sexo con mi pareja y siento que estamos igualmente interesados ​​en hacerlo el uno con el otro. El problema con el que me encuentro es que mi novio tiene cosas muy particulares que dice que encuentra "incómodas" y luego evita tener relaciones sexuales conmigo o se detiene en el medio. Por ejemplo, si le digo que quiero tener sexo, dice que es incómodo y que las cosas deben ser naturales. Dice que no le gusta tener sexo cuando es algo arreglado, pero soy muy verbal para comunicar lo que siento y apesta cuando me rechazan cuando me muero por tenerlo porque él no quiere ya que yo dije quiero. Otro ejemplo, si hablo con él durante el sexo, lo encuentra incómodo. Más recientemente, cuando estábamos haciéndolo y besándonos, comenzó a mover mi ropa interior y le dije “no, quiero que me provoques. Luego se dio la vuelta y dijo: "Arruinas todo" y dejó de tener sexo conmigo. Dijo que iba a burlarse de mí, y ahora lo hice sentir estúpido. No entiendo por qué está tan en contra de comunicarse durante el sexo. Me dijo que dejara de ser molesto y me callara, pero ¿no es el punto comunicarnos entre nosotros sobre lo que queremos que se haga o cómo nos sentimos? Alguien tiene experiencia con esto

6
6
4
466
Comentarios

Premiar

Compartir

Guardar

5 personas aquí

Comentar como godcb

Comentar











Cambiar a Markdown

Ordenar Por: Mejores

Avatar de usuario
nivel 1
bobbothehobbit
Recién llegado
·
hace 2 d
¿Estás seguro de que no cambiaste las eras? suena inmaduro asf


3.0k


Responder
Compartir


Avatar de usuario
nivel 2
pájaro de guerra1928
·
hace 2 d
Sí, te hace darte cuenta de por qué nunca sacó a nadie de su edad.


1.4k


Responder

Compartir


Avatar de usuario
nivel 3
bobbothehobbit
Recién llegado
·
hace 2 d
es incómodo si ella lo menciona, pero está bien si él lo hace. el mundo está realmente lleno de gente extraña


497


Responder

Compartir


Avatar de usuario
nivel 4
Metamorfosis
Karma del 10 % superior mensual
+2
·
hace 1 d
Sí, está organizando el curso de cómo debería funcionar todo en su mente hasta el punto de que no es natural, jajaja. Me parece que no son sexualmente compatibles en la cama.


177


Responder

Compartir


Avatar de usuario
nivel 5
Dexatrón 9000
Mayor
+3
·
hace 1 d
Parece que el tipo es probablemente incompatible con la mayoría de las personas. Eso suena miserable para ser parte de


205


Responder
Compartir


Avatar de usuario
nivel 6
lástima
Votante ávido
+1
·
hace 1 d
Plata
Sí, parece que su pareja ideal es Fleshlight.


59


Responder

Compartir


Avatar de usuario
nivel 6
Metamorfosis
Karma del 10 % superior mensual
+2
·
hace 1 d
O trabaja en estos aspectos o dedica su tiempo a encontrar a alguien que esté de acuerdo con sus puntos de vista/disfrute de sus extrañas reglas y encuentre divertido/emocionante navegar. Esto último parece difícil de hacer, pero bueno, lo que sea que él quiera hacer ... Sin embargo, se siente mal por OP, porque hace que otras personas se sientan mal por su forma de ser sin ser sincero sobre sus gustos / disgustos de una manera respetuosa. forma en que contó la historia. Independientemente de lo extraño que puedas ser, el respeto mutuo es clave en cualquier relación y desacuerdo, ni siquiera parece dispuesto a encontrar un término medio. Definitivamente necesitan sentarse y hablar de esto.


30


Responder
Compartir


Avatar de usuario
nivel 4
miotronombreestonto
·
hace 1 d
Suena como un problema de rendimiento para mí... Inseguridades


7


Responder

Compartir

1 respuesta más


Avatar de usuario
nivel 2
patrón
·
hace 1 d
Sí, el novio de OP suena inseguro, y como si nunca se hubiera comunicado abiertamente sobre el sexo y le resulta estresante hablar o discutir abiertamente las necesidades, etc.

¿Parece que hablar de ello/pedir algo diferente le hace sentir que no es capaz de hacerlo por sí mismo? Pero en realidad es una masculinidad tóxica y una falta de voluntad para recibir comentarios y responder activamente a los deseos explícitos de sus parejas.


141


Responder
Compartir

1 respuesta más


Avatar de usuario
nivel 2
Lozsta
·
hace 2 d
No, en absoluto, mide 30 m, es decir, unos 14 m, si me baso en algo. Ahora tengo 42 años y todavía me siento como de 15 mentalmente. Yo era más maduro sobre el sexo a los 15 de lo que soy ahora, creo.

El tema de la edad no es el problema. El tipo parece que quiere una muñeca sexual, puede tener relaciones sexuales "naturales" en las que ninguno de los dos exprese verbalmente sus deseos, en lugar de lo que suena como un "precoz" (¿cómo se atreve a comunicar cuándo y cómo tiene relaciones sexuales?) de 21 años que es explorando su sexualidad de una manera expresiva.

No parece una relación particularmente gratificante físicamente, pero tal vez tengan otras cosas en común.


111


Responder
Compartir


Avatar de usuario
nivel 3
Paltenburg
Comentarista prolífico
+2
·
hace 1 d
·
editado hace 1 d
tener relaciones sexuales "naturales"

Pfsh.. si imagina eso


22


Responder

Compartir


Avatar de usuario
nivel 4
Lozsta
·
hace 1 d
Imaginar.


2


Responder

Compartir


Avatar de usuario
nivel 5
PosibilidadRough923
Votante ávido
+1
·
hace 1 d
¡Iba a usar mi imaginación pero lo ARRUINASTE!


23


Responder

Compartir


Avatar de usuario
nivel 6
Lozsta
·
hace 1 d
Oh tu. ¡No tienes que imaginarlo ahora, como castigo!


4


Responder

Compartir

1 respuesta más

3 respuestas más


Avatar de usuario
nivel 2
marcodcx
Votante ávido
+1
·
hace 2 d
¡Oye, tengo 21! ¡Ciertamente no soy así! ¿Dónde está el respeto por los jóvenes de 21 años? Realmente vivimos en una sociedad smh.


51


Responder
Compartir


Avatar de usuario
nivel 3
FreakyEsposaFreakyVida
Karma del 1 % superior mensual
+2
·
hace 1 d
Encontrarás a medida que envejeces que crees que eres adulto y luego, cada pocos años, crees que finalmente eres adulto. Luego, cuando miras hacia atrás, eres como... Hombre, yo era un niño absoluto a los 25. No deberíamos permitir que la gente a los 21... Haga todas las cosas para las que necesita una toma de decisiones potente y bien desarrollada. En serio, necesitamos otro soporte como el que tenemos para los adolescentes.

Muchas de estas publicaciones son personas de tu edad que hacen las cosas mal por falta de experiencia. No hay vergüenza en eso. En todo caso, siéntase orgulloso de estar por encima de sus compañeros, pero esté atento a la arrogancia.


37


Responder

Compartir


Avatar de usuario
nivel 4
marcodcx
Votante ávido
+1
·
hace 1 d
Sí, ya me di cuenta de que ese es el caso cuando miro hacia atrás a cómo era cuando era adolescente. Solo digo que ni yo ni ningún otro 21 años que sé se comportaría como el novio de OP. No es que conozca a mucha gente, pero aún así. Y sobre el punto de que las publicaciones aquí de personas que hacen cosas tontas son personas de mi edad, francamente tiendo a asumir que el 90% de las publicaciones aquí son tonterías (incluida esta). Aunque sigue siendo entretenido.


4


Responder
Compartir


Avatar de usuario
nivel 5
FreakyEsposaFreakyVida
Karma del 1 % superior mensual
+2
·
hace 1 d
Bueno, si te das cuenta de que a los 21, estás por delante de la curva. Muchas personas caen en la trampa de "soy un adulto" cuando aún no han formado adecuadamente su facultad de tomar decisiones.

Sí, hay algunos trolls e historias falsas. Pero no es como si estuviera en las páginas de confesiones. En realidad, hay mucha gente aquí que busca ayuda. Así que tienes razón al señalar la discriminación por edad aquí.

Si no eres promedio y no estás con una multitud de personas promedio, la pregunta es ¿cuál está más cerca de toda la sociedad? ¿La pequeña muestra de la sociedad mundial que vemos aquí, o la muestra de la sociedad local con la que interactúas?


5


Responder
Compartir


Avatar de usuario
nivel 3
Snoo-21712
·
hace 1 d
oye? r/sudenlycaralho ?


4


Responder

Compartir


Avatar de usuario
nivel 4
superlikemedaddy
·
hace 1 d
Jajajaja


2


Responder

Compartir


Avatar de usuario
nivel 5
lallenlowe
·
hace 1 d
Es gracioso que recibas votos negativos porque nadie sabe que "kkkkk" significa risa brasileña. jajajajaja


3


Responder

Compartir

5 respuestas más


Avatar de usuario
nivel 3
jugo de durazno626
Recién llegado
·
hace 2 d
·
editado hace 1 d
LOL Bobbo ni siquiera está siendo grosero aquí. Tu respuesta ES realmente un poco inmadura. Editar: ¡Cometí un error!


-2


Responder
Compartir


Avatar de usuario
nivel 4
Mil1512
Votante ávido
+1
·
hace 2 d
Creo que están siendo sarcásticos. Especialmente con la parte de "realmente vivimos en una sociedad".


43


Responder

Compartir


Avatar de usuario
nivel 5
jugo de durazno626
Recién llegado
·
hace 1 d
¡Uy! ¡Lo siento!


2


Responder

Compartir


Avatar de usuario
nivel 4
marcodcx
Votante ávido
+1
·
hace 1 d
Pero reddit no perdona. Si haces un wooosh eres severamente castigado. Lo siento hermano. Te doy un voto a favor de todos modos para luchar contra el sistema;)


2


Responder

Compartir

3 respuestas más


Avatar de usuario
nivel 1
pájaro de guerra1928
·
hace 2 d
La comunicación es muy importante para los actos sexuales, y él invalidar por completo sus sentimientos hacia ella es una bandera roja. El sexo es un acto de equipo y, sinceramente, para un chico de 30 años suena como un adolescente. Y decir que quieres tener sexo de ninguna manera es "programar". Y luego, para colmo, te echa la culpa del problema y te hace sentir mal. Si no llevas mucho tiempo con él o no te preocupas mucho por él, diría que lo dejes; porque no puedes cambiar a una persona que piensa eso drásticamente.


1.2k


Responder
Compartir


Avatar de usuario
nivel 2
W7221975
Comentarista prolífico
+1
·
hace 2 d
Sí, sus acciones y su palabra son cuestiones que necesitan resolución. Si él no está dispuesto a trabajar en la relación, ella necesita encontrar a alguien que lo haga.


129


Responder

Compartir


Avatar de usuario
nivel 2
no_seta_mágica
Comentarista prolífico
+2
·
hace 2 d
A decir verdad, incluso si has estado con él durante mucho tiempo y/o te importa mucho, estaría considerando terminarlo: culpar a tu pareja por todo y negarte a comunicarte sobre los problemas es cómo a veces comienza el abuso.


134


Responder
Compartir

18 respuestas más


Avatar de usuario
nivel 2
cafeesparacerradores
Karma del 5 % superior mensual
+2
·
hace 1 d
Para un treintañero suena como un psicópata. No ponga ese mal en los adolescentes.


18


Responder

Compartir

21 respuestas más


Avatar de usuario
nivel 1
Bobcat_acrobático
Karma del 10 % superior mensual
+2
·
hace 2 d
No. Estás haciendo lo que se supone que debes hacer.

Parece que él quiere que una mujer tenga sexo SÍ, no sexo CON.


467


Responder

Compartir


nivel 2
[eliminado]
·
hace 1 d
Esa es una buena manera de decirlo. Lo que quiero saber es si es tan prescriptivo en otras áreas de la relación. Suena como si quisiera crear una versión de ti que imaginó en su mente en lugar de tu carne y hueso. Si habla sobre este problema, podría ayudar a comprender si el problema está solo dentro o dentro y fuera del dormitorio.


47


Responder
Compartir


Avatar de usuario
nivel 1
boozcruz81
·
hace 2 d
Suena como un maldito bicho raro para mí, especialmente por la edad que tiene.


1.1k


Responder

Compartir


Avatar de usuario
nivel 2
xpgx
Votante ávido
·
hace 2 d
Parece que descubrimos por qué necesita salir con personas 9 años más jóvenes y no puede encontrar a nadie de su edad. como una mujer de 29 años, esto me haría reír en su cara y salir de su cama y nunca volver porque qué carajo lol

Y:

OP; encuentra a alguien que piense que la comunicación durante el sexo es excitante. Obtendrás los mejores orgasmos de tu vida porque te molestarán y tocarán exactamente como te gusta.


476


Responder
Compartir

4 respuestas más


Avatar de usuario
nivel 2
marronDiosa01
Votante ávido
+1
·
hace 2 d
100%


37


Responder

Compartir

4 respuestas más


Avatar de usuario
nivel 1
canastilla
Karma del 1 % superior mensual
+3
·
hace 2 d
suena súper inmaduro. ¿Por qué estás con alguien mucho mayor pero tan inmaduro?


510


Responder

Compartir


Avatar de usuario
nivel 2
zombiekitten823
DESPUÉS
·
hace 2 d
estoy de acuerdo en eso Siento que estoy dispuesto a entender si no le gusta que las cosas se sientan forzadas, supongo, pero no entiendo cómo puedo decirle lo que quiero sin decirlo verbalmente. él lo cambiará y me hará sentir mal por querer tener sexo con él. cuando el lo inicia no hay problema, solo viene cuando lo hago o cuando quiero..


245


Responder

Compartir


Avatar de usuario
nivel 3
metaforazina
Votante ávido
+1
·
hace 2 d
Lo que más me preocupa de esto es que este comportamiento le da el control total de cómo y cuándo ocurre el sexo.

No digo que esto sea necesariamente él estableciendo un patrón de abuso, podría ser un tipo raro, pero junto con la diferencia de edad, me preocupa que esté estableciendo un patrón de control mientras elimina su propia agencia, que a menudo son las etapas iniciales de una relación abusiva.

Aparte de eso, incluso si esto es inocente, tómese el tiempo para considerar cómo serán las cosas si renuncia a todo control sobre su vida sexual para evitar "incomodidades", y si una relación en la que solo él puede iniciar sexo o hablar. durante eso, junto con cualquier otra cosa que él decida que es incómoda, es algo en lo que quieres estar.


311


Responder
Compartir


Avatar de usuario
nivel 4
RobinHarleysCorazón
·
hace 1 d
Este fue en realidad uno de mis primeros pensamientos. Siempre comienza pequeño y crece. Y te encienden y te hacen pensar que eres el que está equivocado.


40


Responder
Compartir


Avatar de usuario
nivel 4
padre_no-binario
Votante ávido
·
hace 17 h
Lo dijiste mejor que yo


2


Responder

Compartir

3 respuestas más


Avatar de usuario
nivel 3
Ariadnepyanfar
Votante ávido
+1
·
hace 1 d
Bueno, puedes iniciar el sexo de forma no verbal besando o acariciando a tu pareja en cualquier habitación en la que se encuentre, y ver si tiene ganas de ir hasta el final. Eso es algo que sucede espontáneamente entre muchas parejas.

Pero yo también encuentro que su estado de ánimo es asesinado por cualquier tipo de conversación. Quiero decir, ese podría ser un reflejo genuino que tiene, y no puede evitarlo... pero una regla de 'no hablar durante el sexo o se acabó' podría llevar a que tantos montículos de arena se conviertan en montañas.

Como el a veces necesario 'por favor, deja de apoyarte en mi cabello (ahora estás golpeando mi cuerpo contra mi cabeza clavada, sin pasar por mi cuello y estoy en una agonía extrema)', que generalmente sale, "mmmff ¡FUERA! ¡FUERA M'Pelo!”.

O 'un poco a la derecha', que es la diferencia entre un orgasmo o ningún orgasmo para ti.

El sexo sin palabras es sexy para mucha gente, pero siendo realistas, todo el sexo no puede ser sin palabras.

Necesita un terapeuta sexual.


51


Responder
Compartir


Avatar de usuario
nivel 4
kbobs19
Votante ávido
·
hace 1 d
'Deja de apoyarte en mi cabello' me hizo reír 😅


3


Responder

Compartir


Avatar de usuario
nivel 3
puentecuatro
Karma del 5 % superior mensual
+3
·
hace 1 d
Ningún compañero debería 'hacerte sentir mal' regularmente. solo estás siendo honesto, no estás usando tus sentimientos como un arma para 'molestarlo', te estás comunicando. :(


26


Responder

Compartir


Avatar de usuario
nivel 3
talitaeli
Karma del 10 % superior mensual
+2
·
hace 1 d
Entonces, para que quede claro, cada vez que pides algo, ¿se "siente forzado"?

¿Está entusiasmado con la perspectiva de obtener solo lo que quiere o necesita si resulta ser lo que él ya tiene ganas de hacer? ¿Te gusta la perspectiva de aprender a manipularlo para que piense que las cosas son idea suya cuando no lo son?

Porque esas son tus elecciones si te quedas en esta relación.


21


Responder
Compartir


Avatar de usuario
nivel 4
kbobs19
Votante ávido
·
hace 1 d
Una respuesta tan perspicaz


2


Responder

Compartir


Avatar de usuario
nivel 3
canastilla
Karma del 1 % superior mensual
+3
·
hace 2 d
definitivamente es una señal de alerta por la forma en que te hace sentir culpable por comunicarte. De nuevo, ¿por qué estás con alguien 9 años mayor, especialmente si es menos maduro que tú?


198


Responder

Compartir


Avatar de usuario
nivel 3
babosa
Karma del 5 % superior mensual
+3
·
hace 1 d
Hmm, ¿un hombre de 30 años que sale con una mujer mucho más joven y actúa extrañamente controlador? Estoy CONMOCIONADO. Chica, busca a alguien que tenga buen sexo contigo sin hacerte sentir que "arruinas" las cosas al pedir lo que quieres. Eres demasiado joven para quedarte con un fanático del control como ese.


51


Responder
Compartir


Avatar de usuario
nivel 3
chillur
Comentarista prolífico
+2
·
hace 1 d
¡RUUUUUUUUUUN!


8


Responder

Compartir


Avatar de usuario
nivel 3
zorra tropezada
·
hace 2 d
Cariño, te está haciendo sentir mal por algo que la mayoría de los hombres anhelan. Las instrucciones sobre cómo obtener el mejor orgasmo cremoso de tu amante están calientes, enséñame cómo complacerte. Deshazte de él y encuentra un LLM menos inseguro y encuentra a alguien dispuesto a hacer lo que sea necesario para hacerte gritar. ¡Te mereces algo mejor, hermana!


77


Responder

Compartir

3 respuestas más


Avatar de usuario
nivel 3
banda matriz
·
hace 2 d
Sí, ¿cuál es exactamente el atractivo de NO poder comunicarse?


28


Responder

Compartir


Avatar de usuario
nivel 3
ImPattMan
Comentarista prolífico
+1
·
hace 1 d
Querida, eso se llama masculinidad frágil...

Es increíblemente insidioso y tóxico, y no va a superarlo sin una perspectiva real. Perspectiva que nunca podrá proporcionar, ni es su trabajo.

Podrías ofrecer terapia de pareja/sexual, y ellos podrían ayudarte, pero supongo que él diría que estás loco y trataría de engañarte.


17


Responder
Compartir


Avatar de usuario
nivel 3
ca1cifer
Votante ávido
+1
·
hace 1 d
Nunca he encontrado que la comunicación no sea sexy. Siempre agradezco cuando una pareja me dice lo que quiere. Este tipo tiene la madurez de una patata.


7


Responder

Compartir


Avatar de usuario
nivel 4
dylann2019
Karma del 5 % superior mensual
+3
·
hace 1 d
eso es generoso Las papas tienen un aura bastante madura sobre ellas. Por lo que puedo decir, este tipo no.


3


Responder

Compartir


Avatar de usuario
nivel 5
ca1cifer
Votante ávido
+1
·
hace 1 d
Tienes razón, la patata es demasiado generosa. Él tiene la madurez de la basura y ella debería sacar su trasero a la acera.


3


Responder
Compartir


Avatar de usuario
nivel 5
kbobs19
Votante ávido
·
hace 1 d
😂


2


Responder

Compartir


Avatar de usuario
nivel 3
gohomerobert
Karma del 5 % superior mensual
+2
·
hace 1 d
Él no quiere que lo quieras. Quiere tener sexo contigo solo si no expresas que lo deseas. Eso asusta.


5


Responder

Compartir


Avatar de usuario
nivel 3
santafe4115
Comentarista prolífico
+2
·
hace 2 d
estás saliendo con un chico de 15 años suena como


14


Responder

Compartir


Avatar de usuario
nivel 3
mrzeebud
Karma del 10 % superior mensual
+3
·
hace 1 d
Su lógica es BS inmadura. Claro, es genial cuando las estrellas se alinean y todo sale perfectamente, pero es una forma realmente estúpida de abordar la vida y las relaciones. En el sexo, también es una buena manera de que tu vida sexual se desmorone y/o viole el consentimiento.

Si aplicas su lógica a cualquier otra faceta de una relación, verás lo loco que es. Si te acercaste a él y quisiste hablar sobre si quiere tener hijos en el futuro, y él respondió: "No quiero hablar de eso. Dejemos que las cosas sucedan naturalmente". ¿Qué pasaría si él diera la misma respuesta acerca de casarse? ¿Qué tal tener una mascota? ¿O abrir la relación?

En el lado del sexo, ¿qué pasa si uno de ustedes tiene una torcedura que quiere probar? ¿Su enfoque es ir a por ello sin preguntar? Esa es una maldita gran bandera roja si lo es. Esa es una buena manera de terminar siendo agredida sexualmente.

Ok, tal vez estoy extrapolando demasiado... Veamos la situación actual: él no quiere comunicarse sobre iniciación o deseos específicos. Él espera que tus deseos se alineen mágicamente y que el sexo suceda. Eso tiende a funcionar al principio de una relación, pero es probable que tu vida sexual esté condenada a largo plazo si no puedes hablar de ello. Te está diciendo que no puedes comunicar tus necesidades. Está diciendo que si el sexo se está volviendo muy poco frecuente, si no satisfaces tus necesidades o si algo te duele, no puedes decírselo. Si lo hace, se enojará y se negará a discutirlo. Significa que las cosas tienen que desmoronarse antes de que suceda cualquier conversación y luego, cuando suceda, se encontrará con hostilidad. A la mierda ese ruido. Deja al hijo de puta ya.


2


Responder
Compartir

4 respuestas más


Avatar de usuario
nivel 2
Flimsy_Honeydew5414
Comentarista prolífico
+1
·
hace 1 d
Esa es exactamente la razón por la cual. Si no fuera un bicho raro inmaduro de 30 años, estaría saliendo con una mujer de su edad.


2


Responder
Compartir


Avatar de usuario
nivel 1
ru_Tc
Votante ávido
·
hace 2 d
·
editado hace 2 d
Eso suena como un pequeño ego muy frágil que pertenece a un fanático del control potencialmente muy grande. Si no hubieras dicho su edad, habría adivinado que tenía 17 en base a esta historia.


232


Responder

Compartir


Avatar de usuario
nivel 2
dolcenbanana
Karma del 1 % superior mensual
+2
·
hace 2 d
Parece muy inseguro.


27


Responder

Compartir

2 respuestas más


Avatar de usuario
nivel 1
La_orca_agnóstica
Votante ávido
+1
·
hace 2 d
Acabo de salir de esta relación con la misma diferencia de edad y problemas similares. Deje esta relación porque créame, desearía haberme ido antes porque los hombres de esa edad se aprovechan de las mujeres más jóvenes porque piensan que somos fáciles de manipular, controlar o tener sexo.


236


Responder

Compartir


Avatar de usuario
nivel 2
W7221975
Comentarista prolífico
+1
·
hace 2 d
Sí, parece que quiere usarla solo para su propio placer.


86


Responder
Compartir


nivel 2
[eliminado]
·
hace 1 d
¿Cuál fue el punto de quiebre para ti?


8


Responder

Compartir


Avatar de usuario
nivel 3
La_orca_agnóstica
Votante ávido
+1
·
hace 1 d
El punto de quiebre fueron tres años en esta relación. Se puso celoso de que estuviera haciendo amigos en lugar de estar con él las 24 horas del día, los 7 días de la semana. Nunca se cuidó a sí mismo y se veía asqueroso. Quería que yo fuera su cuidador (discapacitado) y me puso toda la presión de la relación. Cuando fui a la casa de un amigo, me dijo que pensaba que lo estaba engañando, sin ninguna evidencia. Le dije que necesito respeto y que Él no dicta con quién paso mi tiempo. Rompí con el. Nos consideró comprometidos. Este fue un LDR, y duró tres años. Estoy con mi actual novio en la vida real desde hace seis meses y es día y noche. Mi novio nunca me presionó para tener sexo ni me hizo sentir que le debía sexo. Tenemos una comunicación saludable y siento que tengo un valor genuino en lugar de sentirme como un objeto sexual.


7


Responder
Compartir


Avatar de usuario
nivel 2
ElDemonioCzarina
Votante ávido
·
hace 1 d
Si alguien (no una relación seria, al menos de mi parte) hiciera esto en una situación similar. Trató de convencerme de que ir a su casa y tener sexo con él sería mejor que pasar el rato con mis amigos a través de Discord.

Para ser un profesor de psicología y sociología, era una verdadera mierda manipulando a alguien ~ 15 años menor que él.

Me alegré de cortarlo. Él era raro.


3


Responder
Compartir

1 respuesta más


Avatar de usuario
nivel 1
remoto
Karma del 5 % superior mensual
+2
·
hace 2 d
Es inseguro como la mierda. Santa mierda. El tipo no puede aceptar que se le adelante y luego se desquita contigo. No importa cuál sea su problema, si estás a punto de decirle a alguien "lo arruinas todo", es una gran señal de que las cosas están terminando o terminando.

La comunicación es el tema más importante para cualquier tipo de relación, y él está siendo un imbécil y tratando de evitarlo, haciéndote sentir como una mierda. A la mierda

Estás bien cariño. Tienes que encontrar un chico al que le guste una chica que hable. Nunca lo había hecho antes, pero este último tipo con el que estaba habla y me impulsó jodidamente willlllddddd. Me derretí... tengo una sonrisa estúpida solo de pensarlo jajaja. Me encanta. Relajaba mis músculos y, a veces, incluso podía ordenarme que me eyaculara.


201


Responder
Compartir


Avatar de usuario
nivel 2
tejón rojo91
Comentarista prolífico
+2
·
hace 2 d
A la mierda

Pues ya ves....


35


Responder

Compartir

5 respuestas más


Avatar de usuario
nivel 1
brocodaily
Recién llegado
·
hace 2 d
Bandera roja. Controlando, invalidando tus sentimientos, gaslighting, infantil y egoísta.

Sal de esa relación. No vale la pena y nunca, ni una vez, avergonzarse de desear la comunicación y expresar sus sentimientos.


50


Responder
Compartir


Avatar de usuario
nivel 1
Cumplido-Wind877
Recién llegado
·
hace 2 d
Si una mujer con la que estoy saliendo me pidiera tener sexo, me sentiría más que halagado y lo haría. Me gusta comunicar. No creo que ustedes sean tan compatibles


16


Responder

Compartir

1 respuesta más


Avatar de usuario
nivel 1
Zombie-Belle
·
hace 1 d
Cómo te atreves a decir "lo arruinas todo"... patético. sal cuanto antes!!


16


Responder

Compartir


Avatar de usuario
nivel 1
ImposibleSquish
Karma del 5 % superior mensual
+2
·
hace 2 d
Wtf... No tiene habilidades de comunicación. Honestamente, no creo que sea material de novio.


46


Responder

Compartir


Avatar de usuario
nivel 1
peroprimerocarbohidratos
Karma del 5 % superior mensual
+3
·
hace 2 d
·
editado hace 1 d
No estás haciendo nada malo, y su comportamiento suena muy insensible e invalidante.

Mi reacción instintiva: tiene algunos problemas sexuales que realmente necesita resolver. Tal vez vergüenza y vergüenza en torno al sexo, problemas de autoestima/inseguridades, o una combinación de varias cosas. Tal vez es muy particular sobre la forma en que se comunican las cosas durante el sexo: palabras específicas elegidas, tono de voz, expresiones faciales. Admito que me gustan las conversaciones sucias específicas. Por ejemplo, la misma oración dicha de una manera será un encendido instantáneo, y dicha de manera ligeramente diferente será un apagado instantáneo.

Ten una conversación con él y hazle saber que te gusta comunicarte durante el sexo y que te sientes herida por la forma en que ha reaccionado. Intente suavemente que se abra sobre lo que encuentra incómodo y por qué, luego, si es posible, encuentre un lenguaje sexual común con él que ambos encuentren sexy. Es posible que simplemente no disfrute de ninguna conversación sucia o comunicación no esencial durante el sexo.


12


Responder
Compartir


Avatar de usuario
nivel 1
Abismo_mirando_detrás
Comentarista prolífico
+1
·
hace 2 d
Ewww, Jebus... Deshazte de este tipo. Lo siento, pero suena súper inmaduro y manipulador.

Quiero decir, ¿decirte que "simplemente estés callado" cuando intentas verbalizar tus deseos? Sin fallar.

Si algo en estas situaciones es "incómodo", es él. Tíralo hacia atrás y atrapa uno nuevo.


102


Responder

Compartir


Avatar de usuario
nivel 1
Mshalopd1
Karma del 5 % superior mensual
+2
·
hace 2 d
Lmao. A la mierda con este tipo. Puedes encontrar a alguien a quien le encantará hablar contigo en la cama, que esté dispuesto a escuchar tus solicitudes y que se emocione cuando inicies.


11


Responder
Compartir


Avatar de usuario
nivel 1
blahwowblah
·
hace 2 d
Parece controlador. Es posible que desee considerar su relación con él.


64


Responder

Compartir


Avatar de usuario
nivel 1
kempeth
·
hace 2 d
Me suena a manipulador. No se le permite iniciar el sexo. No se le permite hablar durante particularmente cuando expresa sus necesidades y deseos.

Él no te ve como una persona. Eres su muñeca de mierda.


26


Responder

Compartir


Avatar de usuario
nivel 1
Ratsubo
Recién llegado
·
hace 2 d
Bruh, ese muchacho tiene algunos problemas, maldita sea.


9


Responder

Compartir


Avatar de usuario
nivel 1
Gran-Veterinario-823
Comentarista prolífico
+1
·
hace 2 d
Parece que tiene un gran ego sensible.


7


Responder

Compartir


Avatar de usuario
nivel 1
puentecuatro
Karma del 5 % superior mensual
+3
·
hace 1 d
Está avergonzado de tener confianza y poseer su energía sexual (desearte y mostrarla), y es idealista, mi ex era similar, arruinó el sexo por completo una vez porque teníamos una película de fondo y no pude evitar reírme. estaba diciendo que arruiné todo el estado de ánimo al detenerme y reír, así que "¿cuál es el punto ahora?" Estaba bastante sorprendido por la reacción exagerada.

Obviamente quieren que el sexo sea una fantasía idealizada. Tampoco suena como alguien que se preocupa por ti y se queja más de lo que considera. Esto probablemente se transfiera a más áreas de su relación. Pídele que se comprometa con esto porque el sexo se extinguirá entre ustedes si no encuentras una manera de hacer que funcione.

Mi ex también me llamaría 'molesto...' y aquí estoy, soltera, ¡más feliz que nunca!


9


Responder
Compartir


Avatar de usuario
nivel 1
Vuelo de orquídeas
Karma del 10 % superior mensual
+2
·
hace 1 d
“Arruinas todo” — que ahí mismo hay una razón para irse. No digo que sea necesariamente abusivo, pero llamarte molesto y decir cosas como esta es un comportamiento abusivo. No es así como se comporta alguien que dice preocuparse por otra persona. Estás comunicando tus necesidades de una manera respetuosa y sexy, y él está tratando de moldearte en lo que quiere que seas a través de la vergüenza. Este tipo apesta. No tienes idea de cuántos hombres AMARÍAN a una mujer de tu edad que realmente pueda comunicar sus necesidades y deseos.


*/


