$(document).ready(function(){


let costoDolar = $("[name=UniPUCD]");
let costoSol = $("[name=UniPUCS]");
let ventaDolar = $("[name=UniPUVD]");
let ventaSol = $("[name=UNIPUVS]");
let margen = $("[name=UniMarg]");	

function calculateTotals()
{

}

function calcular()
{
	console.log("producto" , producto);

	let cantidad_producto = $("[name=UniEnte]");
	let cantidad_unidad = $("[name=UniMedi]");

	let producto_cantidad = cantidad_producto.val();
	let unidad_cantidad   = cantidad_unidad.val();


	if(validate_number(producto_cantidad) && validate_number(unidad_cantidad)){

		producto_cantidad = Number(producto_cantidad);
		unidad_cantidad   = Number(unidad_cantidad);

		function calculo( valor , show = false )
		{
			let v;

			if( producto_cantidad > unidad_cantidad ){
				v = valor * producto_cantidad;
			}

			else if( producto_cantidad < unidad_cantidad ){
				v = valor / unidad_cantidad;				
			}			

			else {
				v = valor;
			}

			return fixValue(v);
		}

		let value = 0;
		$("[name=UniPeso]").val( calculo(producto.peso , "peso" ));		
		$("[name=UniPUCD]").val( calculo(producto.costo_dolar , "costo_dolar"));				
		$("[name=UniPUCS]").val( calculo(producto.costo_sol));				
		$("[name=UniPUVD]").val( calculo(producto.venta_dolar));				
		$("[name=UNIPUVS]").val( calculo(producto.venta_sol));
	}

}


function calculoPorcentaje(valor, porc) 
{
	console.table(valor,porc);
	valor = Number(valor);
	porc = Number(porc);
	return ((valor / 100) * porc);
}

	function getValorConPorcentaje(valor, porc) {
		return calculoPorcentaje(valor, porc) + Number(valor);
	}



function calcularTipoDeCambio(val, multiplicacion = true)
{
	return multiplicacion ? (val * tipo_cambio) : val / tipo_cambio;
}

function calcularPrecioVenta()
{
	let margenValue = Number(margen.val());

	console.log({margenValue})

	let newVentaDolar = fixedNumber(getValorConPorcentaje(costoDolar.val(), margenValue));
	let newVentaSol = fixedNumber(getValorConPorcentaje(costoSol.val(), margenValue) );

	ventaDolar.val(newVentaDolar);
	ventaSol.val(newVentaSol);
}


function setNuevoMargen(isSol)
{
	let margenValue = 0;

	let precioVentaSol = Number(ventaSol.val());
	let precioVentaDolar = Number(ventaDolar.val());
	let precioCostoSol = Number(costoSol.val());
	let precioCostoDolar = Number(costoDolar.val());

	// let valueSol = 0;
	// let costoVentaDolar = 0;
	// let costoVentaSol = 0;
	// let costoDolar = $("[name=UniPUCD]");
	// let costoSol = $("[name=UniPUCS]");
	// let ventaDolar = $("[name=UniPUVD]");
	// let ventaSol = $("[name=UNIPUVS]");
	// let margen = $("[name=UniMarg]");	


	margenValue = isSol ? (((precioVentaSol / precioCostoSol) - 1) * 100) : (((precioVentaDolar / precioCostoDolar) - 1) * 100);

	// let margen = 0;
	
	// if (sol) {
	// 	margen = ((this.getPrecioVentaSol() / this.getCostoSol()) - 1) * 100;
	// 	let precioVentaDolar = this.dividedByTipoCambio(this.getPrecioVentaSol());
	// 	this.setPrecioVentaDolar(precioVentaDolar);
	// }

	// else {
	// 	margen = ((this.getPrecioVentaDolar() / this.getCostoDolar()) - 1) * 100;
	// 	let precioVentaSol = this.multipledByTipoCambio(this.getPrecioVentaDolar());
	// 	this.setPrecioVentaSol(precioVentaSol);
	// }

	margen.val( fixedNumber(margenValue));
}




function setTipoCambio()
{
	window.tipo_cambio = Number($("[name=tipo_cambio]").val());
}

// Precio costo


function setCostoSol()
{
	let val = fixedNumber(calcularTipoDeCambio(Number($(this).val()) , true ));
	costoSol.val(val);
	calcularPrecioVenta();
}


function setCostoDolar() 
{
	let val = fixedNumber(calcularTipoDeCambio(Number($(this).val()), false ));
	costoDolar.val(val);
	calcularPrecioVenta();
}


// Precio de venta

function setVentaCostoSol()
{
	let val = fixedNumber(calcularTipoDeCambio(Number($(this).val()), true));
	ventaSol.val(val);
	setNuevoMargen(true);
}


function setVentaCostoDolar() 
{
	let valueSol = Number($(this).val());
	let val = calcularTipoDeCambio(valueSol, false );
	ventaDolar.val(fixedNumber(val));
	setNuevoMargen(false);

}

function events ()
{
	$(".show_form").on('click', function(){ $(".form_create").toggle(); });
	$("[name=UniEnte],[name=UniMedi]").on('keyup' , calcular );
	margen.on('keyup', () => calcularPrecioVenta() );

	costoDolar.on('keyup', setCostoSol );
	costoSol.on('keyup', setCostoDolar );


	

	ventaDolar.on('keyup', setVentaCostoSol );
	ventaSol.on('keyup', setVentaCostoDolar );

	
	setTipoCambio();
}

init( events );
// UniEnte

})
