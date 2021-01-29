<?php

namespace GuiAssemany\UtmForwarder\Middleware;

use Closure;
use Illuminate\Http\Request;
use GuiAssemany\UtmForwarder\AnalyticsBag;

class TrackAnalyticsParametersMiddleware
{
    protected AnalyticsBag $analyticsBag;

    public function __construct(AnalyticsBag $analyticsBag)
    {
        $this->analyticsBag = $analyticsBag;
    }

    public function handle(Request $request, Closure $next)
    {
        $this->analyticsBag->putFromRequest($request);

        return $next($request);
    }
}
