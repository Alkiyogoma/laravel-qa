<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use DB;

class Handler extends ExceptionHandler {

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    function createLog($e) {
        $line = @$e->getTrace()[0]['line'];
        $object = [
            'error_message' => $e->getMessage() . ' on line ' . $line . ' of file ' . @$e->getTrace()[0]['file'],
            'file' => @$e->getTrace()[0]['file'],
            "url" => url()->current(),
            'error_instance' => get_class($e),
            'request' => json_encode(request()->all()),
            "schema_name" => 'admin',
            'created_by' => session('id'),
            'created_by_table' => session('table')
        ];
        if (!preg_match('/ValidatesRequests.php/i', @$e->getTrace()[0]['file']) || !preg_match('/Router.php/i', @$e->getTrace()[0]['file'])) {
           // DB::table('admin.error_logs')->insert($object);
        }

        $line = @$e->getTrace()[0]['line'];
        $err = "<br/><hr/><ul>\n";
        $err .= "\t<li>date time " . date('Y-M-d H:m', time()) . "</li>\n";
        $err .= "\t<li>Made By: " . session('id') . "</li>\n";
        $err .= "\t<li>usertype " . session('usertype') . "</li>\n";
        $err .= "\t<li>error msg: [" . $e->getCode() . '] ' . $e->getMessage() . ' on line ' . $line . ' of file ' . @$e->getTrace()[0]['file'] . "</li>\n";
        $err .= "\t<li>url: " . url()->current() . "</li>\n";
        $err .= "</ul>\n\n";

        $filename ='sanlam' . str_replace('-', '_', date('Y-M-d')) . '.html';
        if(@$e->getTrace()[0]['file'] != '/vendor/laravel/framework/src/Illuminate/Routing/RouteCollection.php')
        error_log($err, 3, dirname(__FILE__) . "/../../storage/logs/" . $filename);
    }

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Throwable $exception) {
        $this->createLog($exception);
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, \Throwable $exception) {
        $this->createLog($exception);
         //   $this->unauthenticated($exception);
        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            return redirect()->guest(route('login'));
        }
        
     /*   if ($this->isHttpException($exception)) {
            if ($exception->getStatusCode() == 404) {
                return response()->view('errors.' . '404', [], 404);
            }
            if ($exception->getStatusCode() == 500) {
                return response()->view('errors.' . '500', [], 500);
            }
        } */
       return parent::render($request, $exception);
    }
   
    /**
     * Convert an authentication exception into an unauthenticated response.
     */

    protected function unauthenticated($request, AuthenticationException $exception) {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }

}
