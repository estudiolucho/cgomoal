<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\User;
use Illuminate\Support\Facades\Input;
Route::get('/', function () {
    return view('welcome');
});


//rutas para payment controller
Route::post('admin/payment/findcredit', ['uses'=>'PaymentController@findcredit', 'as'=> 'payment.findcredit'])->middleware('auth','admin');
Route::get('admin/payment/{user_id}/posfindcredit', ['uses'=>'PaymentController@posfindcredit', 'as'=> 'payment.posfindcredit'])->middleware('auth');
Route::get('admin/payment/{ed}/list', ['uses'=>'PaymentController@list', 'as'=> 'payment.list'])->middleware('auth');
Route::get('admin/payment/{payment}/createbycredit', ['uses'=>'PaymentController@createbycredit', 'as'=> 'payment.createbycredit'])->middleware('auth','admin');
Route::post('admin/payment/process', ['uses'=>'PaymentController@process', 'as'=> 'payment.process'])->middleware('auth','admin');
Route::get('admin/payment/created',['uses'=>'PaymentController@created', 'as'=>'payment.created'])->middleware('auth','admin');

//rutas para cashflow controller
Route::get('admin/cashflow/created',['uses'=>'CashFlowController@created', 'as'=>'cashflow.created'])->middleware('auth','admin');
Route::post('reports/cashflow/list', ['uses'=>'CashFlowController@list', 'as'=> 'reports.cashflow.list'])->middleware('auth','admin');
//solicita id de registro
Route::get('admin/cashflow/select', ['uses'=>'CashFlowController@select', 'as'=> 'cashflow.select'])->middleware('auth','admin');
Route::get('admin/cashflow/editar', ['uses'=>'CashFlowController@editar', 'as'=> 'cashflow.editar'])->middleware('auth','admin');
//pendiente revisar
Route::get('admin/cashflow/delete', ['uses'=>'CashFlowController@delete', 'as'=> 'cashflow.delete'])->middleware('auth','admin'); 
Route::get('admin/cashflow/search', ['uses'=>'CashFlowController@search', 'as'=> 'cashflow.search'])->middleware('auth','admin');

//rutas para reportes
Route::get('reports/payments/search', ['uses'=>'PaymentController@search', 'as'=> 'reports.payments.search'])->middleware('auth');
Route::post('reports/payments/list', ['uses'=>'PaymentController@listByDate', 'as'=> 'reports.payments.list'])->middleware('auth');
Route::post('admin/payment/inputCredit', ['uses'=>'PaymentController@inputCredit', 'as'=> 'payment.inputCredit'])->middleware('auth');
Route::get('reports/payments/searchbycredit', ['uses'=>'PaymentController@searchbycredit', 'as'=> 'reports.payments.searchbycredit'])->middleware('auth');
Route::get('reports/contributions/search', ['uses'=>'ContributionController@search', 'as'=> 'reports.contributions.search'])->middleware('auth');
Route::post('reports/contributions/list', ['uses'=>'ContributionController@listByDate', 'as'=> 'reports.contributions.list'])->middleware('auth');
Route::get('reports/contributions/search2', ['uses'=>'ContributionController@search2', 'as'=> 'reports.contributions.search2'])->middleware('auth');
Route::post('reports/contributions/grouplistbyuser', ['uses'=>'ContributionController@grouplistByuser', 'as'=> 'reports.contributions.groupbyuser'])->middleware('auth');
Route::get('reports/contributions/search3', ['uses'=>'ContributionController@search3', 'as'=> 'reports.contributions.search3'])->middleware('auth');
Route::post('reports/contributions/listbydocument', ['uses'=>'ContributionController@listByDocument', 'as'=> 'reports.contributions.listbydocument'])->middleware('auth');
Route::get('reports/contributions/search4', ['uses'=>'ContributionController@search4', 'as'=> 'reports.contributions.search4'])->middleware('auth');
Route::post('reports/contributions/listdocument', ['uses'=>'ContributionController@listDocument', 'as'=> 'reports.contributions.listdocument'])->middleware('auth');
Route::get('reports/expenses/search', ['uses'=>'ExpenseController@search', 'as'=> 'reports.expenses.search'])->middleware('auth');
Route::post('reports/expenses/list', ['uses'=>'ExpenseController@listByDate', 'as'=> 'reports.expenses.list'])->middleware('auth');
//Route::get('admin/payment/plist', ['uses'=>'PaymentController@list', 'as'=> 'report.plist']
//)->middleware('auth');

//rutas para contribution controller
Route::get('admin/contribution/retirement',['uses'=>'ContributionController@retirement','as'=>'contribution.retirement'])->middleware('auth');
Route::post('admin/contribution/storeretirement',['uses'=>'ContributionController@storeRetirement','as'=>'contribution.storeretirement'])->middleware('auth');
Route::get('admin/contribution/{user_id}/find', ['uses'=>'ContributionController@find', 'as'=> 'contribution.find'])->middleware('auth');
Route::get('admin/contribution/created',['uses'=>'ContributionController@created', 'as'=>'contribution.created'])->middleware('auth','admin');
//rutas para expense controller
//evita que se duplique un gasto al recargar la pagina index
Route::get('admin/expense/created',['uses'=>'ExpenseController@created', 'as'=>'expense.created'])->middleware('auth','admin');


//para bajar y subir registros en excel
//pagos por lapso
Route::get('reports/payments/downloadExcel/{type}', 'PaymentController@downloadExcel');
//aportes detallados
Route::get('reports/importExport', 'ContributionController@importExport'); //no es necesaria
Route::get('reports/downloadExcel/{type}', 'ContributionController@downloadExcel');
Route::post('reports/importExcel', 'ContributionController@importExcel');
//aportes agrupados
Route::get('reports/downloadExcel2/{type}', 'ContributionController@downloadExcel2');
Route::post('reports/importExcel2', 'ContributionController@importExcel2');
//aportes por fecha por socio
Route::get('reports/downloadExcel3/{type}', 'ContributionController@downloadExcel3');
//aportes por socio
Route::get('reports/downloadExcel4/{type}', 'ContributionController@downloadExcel4');
//gastos detallados
Route::get('reports/expenses/downloadExcel/{type}', 'ExpenseController@downloadExcel');
//registro de E/S
Route::get('reports/downloadExcelFlujo/{type}', 'CashFlowController@downloadExcel');


//rutas para credit controller
Route::post('admin/credit/findUser', ['uses'=>'CreditController@findUser', 'as'=> 'credit.findUser'])->middleware('auth','admin');
Route::get('admin/credit/{user}/createbyuser', ['uses'=>'CreditController@createbyuser', 'as'=> 'credit.createbyuser'])->middleware('auth','admin');
Route::post('admin/credit/amortization', ['uses'=>'CreditController@amortization', 'as'=> 'credit.amortization'])->middleware('auth','admin');
Route::post('admin/credit/confirm', ['uses'=>'CreditController@confirm', 'as'=> 'credit.confirm'])->middleware('auth','admin');
Route::get('admin/credit/created',['uses'=>'CreditController@created', 'as'=>'credit.created'])->middleware('auth','admin');
Route::get('admin/credit/generaInt', ['uses'=>'CreditController@generaInt', 'as'=> 'credit.generaInt'])->middleware('auth','admin');
Route::get('admin/credit/simulator', ['uses'=>'CreditController@simulator', 'as'=> 'credit.simulator'])->middleware('auth');
Route::post('admin/credit/amortizationdemo', ['uses'=>'CreditController@amortizationdemo', 'as'=> 'credit.amortizationdemo'])->middleware('auth');
Route::get('admin/credit/canceled', ['uses'=>'CreditController@canceled', 'as'=> 'credit.canceled'])->middleware('auth','admin');




Route::get('admin/user/{id}/findcredit', ['uses'=>'UserController@findCredit', 'as'=> 'user.findCredit']
)->middleware('auth','admin');
Route::get('admin/user/list', ['uses'=>'UserController@list', 'as'=> 'user.list'])->middleware('auth','admin');

//buscar Usuarios por criterios
Route::any ( '/search', function () {
    $q = Input::get ( 'q' );
    if($q != ""){
	    $user = User::where ( 'name', 'LIKE', '%' . $q . '%' )
	    	->orWhere ( 'email', 'LIKE', '%' . $q . '%' )
	    	->orWhere ( 'document', 'LIKE', '%' . $q . '%' )
	    	->paginate (5)->setPath ( '' );
	    $pagination = $user->appends ( array ('q' => Input::get ( 'q' ) ) );
	    if (count ( $user ) > 0)
	        return view ( 'admin.users.index' )->with('users', $user )->withQuery ( $q );
    }
    return view('admin.template.messages')->with('text','No se encuentra usuario !. Intenta otro criterio (Cedula, Nombre, Correo)');
    //return view ( 'welcome' )->withMessage ( 'No se encuentra usuario. Intenta buscar de nuevo !' );
})->middleware('auth','admin');



Route::group(['prefix'=>'admin','middleware'=> ['auth','admin']],
	function(){
		Route::resource('econcept','ExpenseConceptsController');
		Route::get('econcept/{id}/destroy',[
			'uses'=> 'ExpenseConceptsController@destroy',
			'as'=>'admin.econcept.destroy'
			]);
		Route::resource('expense'     ,'ExpenseController');
		Route::resource('cconcept'    ,'ContributionConceptsController');
		Route::resource('contribution','ContributionController');
		Route::resource('credit'      ,'CreditController');
		Route::resource('payment'     ,'PaymentController');
		Route::resource('user'        ,'UserController');
		Route::resource('cashflow'        ,'CashFlowController');
	}
);


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


// SECCION DE EJEMPLOS
Route::get('expenses', function () {
    echo  "esta es la seccion de articulos";
});

/*
Route::get('expense/{nombre}', function ($nombre) {
    echo  "esta es la seccion de articulo el nombre ingresado es: ". $nombre; //http://localhost:8000/expense/alguntexto
    //return view('welcome');
});*/
Route::get('expense/{nombre}', ['uses'=>'ExpenseController@view','as'=>'articlesView'
	]);
Route::get('expense2/{nombre}', ['uses'=>'ExpenseController@view2','as'=>'articlesView2'
	]); 


Route::get('expens/{nombre?}', function ($nombre = "no hay nombre") {
    echo  "esta es la seccion de articulo el nombre ingresado es: ". $nombre; //http://localhost:8000/expens/ puede o no tener alguntexto
    //return view('welcome');
	}
);
Route::get('expen/{nombre?}', [
	'as' => 'nombre',
	'uses'=>'UsersController@view'  //llamaria al metodo view del controlador UserControler
]);
//grupo de rutas

Route::group(
	['prefix'=>'exp'],
	function (){
		Route::get('view/{expense?}',function($expense="Vacio"){
			return $expense ;  //http://localhost:8000/exp/view
		});
	 }
	
);
//TERMINAN LOS EJEMPLOS

