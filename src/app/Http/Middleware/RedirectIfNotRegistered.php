namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotRegistered
{
public function handle(Request $request, Closure $next)
{
// ユーザーがログインしていない場合はログイン画面へ
if (!Auth::check()) {
return redirect()->route('login');
}

// ユーザーが未登録（`users` テーブルにデータがない）なら会員登録画面へ
if (Auth::user() === null) {
return redirect()->route('register.step1');
}

return $next($request);
}
}