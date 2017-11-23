Hi,

As per your request, here are our records on the following cross connect to INEX.

@if( trim( $ppp->getDescription() ) )
    **Description**: {{ $ppp->getDescription() }}
@endif

```
Colo Reference:  {{ $ppp->getColoCircuitRef() }}
Patch panel:     {{ $ppp->getPatchPanel()->getName() }}
Port:            {{ $ppp->getName() }} @if( $ppp->hasSlavePort() ) *(duplex port)* @endif

State:           {{ $ppp->resolveStates() }}
@if( $ppp->getCeaseRequestedAt() )
Cease requested: {{  $ppp->getCeaseRequestedAt()->format('Y-m-d') }}
@endif
@if( $ppp->getConnectedAt() )
Connected on:    {{  $ppp->getConnectedAt()->format('Y-m-d') }}
@endif
```

@if( $ppp->hasPublicFiles() )
We have attached all the documentation which we have on file regarding this connection.
@endif

@if( strlen( trim( $ppp->getNotes() ) ) )
We have also recorded the following notes:

@foreach( explode( "\n", $ppp->getNotes() ) as $l )
> {{$l}}
@endforeach

@endif

@include('patch-panel-port/emails/signature')
