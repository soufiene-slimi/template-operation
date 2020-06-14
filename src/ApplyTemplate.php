<?php

namespace SoufieneSlimi\TemplateOperation;
use Template;

use Closure;

class ApplyTemplate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $template = Template::find($request->template_id);

        if ($template) {
            $template->apply();
        }

        return $next($request);
    }
}
