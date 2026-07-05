<div class="min-h-screen bg-ink">
    <div class="bg-ink text-cream px-4 py-4 flex items-center justify-between">
        <a href="{{ route('events.index') }}" class="w-8 h-8 bg-surface-2 flex items-center justify-center shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></a>
        <div class="text-right ml-3">
            <h1 class="font-display text-lg font-black uppercase leading-none">{{ $event->name }}</h1>
            <p class="font-body text-[10px] text-cream/40 uppercase tracking-[0.12em] mt-0.5">{{ $event->venue->name }}@if($city), {{ $city }}@endif · {{ $event->start_time->format('d M Y') }}</p>
            @if(!$event->sales_open)<span class="inline-block mt-1 font-body text-[10px] font-bold uppercase tracking-[0.15em] bg-error text-white px-2 py-0.5">Ditutup</span>@endif
        </div>
    </div>

    @if($event->banner)<img src="{{ Storage::url($event->banner) }}" class="w-full h-40 object-cover">@endif

    {{-- Venue Map --}}
    <div class="bg-cream mx-4 mt-2 rounded-2xl p-4">
        <p class="font-body text-[11px] font-semibold text-mid-gray uppercase tracking-[0.06em] mb-3">Pilih Zona Anda</p>
        <svg viewBox="0 0 100 70" class="w-full" xmlns="http://www.w3.org/2000/svg">
            <rect x="0" y="0" width="100" height="70" fill="#F5F0E8"/>
            <rect x="5" y="1" width="90" height="6" rx="1.5" fill="#000"/>
            <text x="50" y="5" fill="#E8FF00" font-size="2" font-weight="700" text-anchor="middle" font-family="'Inter',sans-serif" letter-spacing="1.5">PANGGUNG</text>

            @php $maxQ=collect($zoneData)->max('quota')?:1; $sc=40/sqrt($maxQ); $hp=collect($zoneData)->filter(fn($z)=>($z['position_x']??0)>0||($z['position_y']??0)>0)->isNotEmpty(); @endphp
            @foreach($zoneData as $idx=>$zone)
                @php
                    $bw=max(10,sqrt($zone['quota'])*$sc); $bh=max(6,$bw*0.5);
                    $cx=$hp?($zone['position_x']??50):(25+$idx*25); $cy=$hp?($zone['position_y']??30):(18+$idx*16);
                    $x=$cx-$bw/2; $y=$cy-$bh/2;
                    $sel=($selectedZone['id']??null)===$zone['id']; $sold=$zone['soldOut'];
                    $clk=(!$sold&&$event->sales_open)?'selectZone('.$zone['id'].')':'';
                    $tc=$sold?'#fff':(stripos($zone['name'],'vip')!==false?'#000':'#fff');
                @endphp
                <g class="{{$clk?'cursor-pointer':'cursor-not-allowed'}}" wire:click="{{$clk}}">
                    <rect x="{{$x}}" y="{{$y}}" width="{{$bw}}" height="{{$bh}}" rx="2" fill="{{$sold?'#9E9E9E':$zone['color']}}" fill-opacity="{{$sold?'0.5':'0.9'}}" stroke="{{$sel?'#000':'transparent'}}" stroke-width="{{$sel?'2':'0'}}"/>
                    <text x="{{$cx}}" y="{{$cy-1}}" fill="{{$tc}}" font-size="{{min($bw*0.2,2.8)}}" font-weight="700" text-anchor="middle" font-family="'Inter',sans-serif">{{$zone['name']}}</text>
                    @if($sold)<text x="{{$cx}}" y="{{$cy+3}}" fill="#fff" font-size="{{min($bw*0.13,2)}}" font-weight="700" text-anchor="middle" opacity="0.8">SOLD OUT</text>
                    @else<text x="{{$cx}}" y="{{$cy+2.5}}" fill="{{$tc}}" font-size="{{min($bw*0.11,1.6)}}" text-anchor="middle" opacity="0.85">IDR {{number_format($zone['price'],0,',','.')}}</text>@endif
                </g>
            @endforeach

            <g transform="translate(5,63)">@foreach($zoneData as $zone)<g transform="translate({{$loop->index*28}},0)"><rect x="0" y="0" width="3" height="3" rx="0.5" fill="{{$zone['soldOut']?'#9E9E9E':$zone['color']}}" opacity="{{$zone['soldOut']?'0.5':'1'}}"/><text x="4" y="2.5" fill="#5F5E5A" font-size="1.4" font-weight="600" font-family="'Inter',sans-serif">{{$zone['name']}}</text></g>@endforeach</g>
        </svg>
    </div>

    {{-- Popup --}}
    @if($selectedZone)
    <div class="bg-ink text-cream mx-4 mt-2 rounded-2xl p-5">
        <div class="flex items-start justify-between mb-3"><h3 class="font-display text-xl font-black uppercase">{{$selectedZone['name']}}</h3><button wire:click="selectZone({{$selectedZone['id']}})" class="text-cream/30 hover:text-cream"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button></div>
        <div class="border-t border-white/10 pt-3 flex items-center justify-between mb-3"><span class="font-body text-xs text-white/50 uppercase tracking-[0.06em]">Harga per tiket</span><span class="font-display text-xl font-black text-accent">IDR {{number_format($selectedZone['price'],0,',','.')}}</span></div>
        <div class="flex items-center justify-between mb-4"><span class="font-body text-xs text-white/50 uppercase tracking-[0.06em]">Sisa kuota</span><span class="font-body text-sm font-bold">{{number_format($selectedZone['remaining'])}} tiket</span></div>
        @if(!$selectedZone['soldOut']&&$event->sales_open)
            @auth
                @if($event->queue_enabled)
                    <a href="{{route('queue.show', $event)}}?section={{$selectedZone['id']}}" class="block w-full text-center font-body text-sm font-bold uppercase tracking-[0.12em] bg-accent text-ink rounded-pill py-3 mt-4 hover:bg-cream transition-colors">Masuk Antrian · {{$selectedZone['name']}}</a>
                @else
                    <a href="{{route('orders.create',['event'=>$event,'section'=>$selectedZone['id']])}}" class="block w-full text-center font-body text-sm font-bold uppercase tracking-[0.12em] bg-accent text-ink rounded-pill py-3 mt-4 hover:bg-cream transition-colors">Beli Tiket · {{$selectedZone['name']}}</a>
                @endif
            @else
                <a href="{{route('login')}}" class="block w-full text-center font-body text-sm font-bold uppercase tracking-[0.12em] bg-accent text-ink rounded-pill py-3 mt-4 hover:bg-cream transition-colors">Login untuk Beli Tiket</a>
            @endauth
        @endif
    </div>
    @endif

    <div class="px-4 pb-5 pt-2">
        @if(!$event->sales_open)<div class="block w-full text-center font-body text-sm font-bold uppercase tracking-[0.15em] bg-surface-2 text-muted rounded-pill py-3.5">Penjualan Ditutup</div>@elseif(!$selectedZone)
            @auth
                @if($event->queue_enabled)
                    <div class="block w-full text-center font-body text-xs font-semibold uppercase tracking-[0.1em] bg-surface-2 text-muted rounded-pill py-3.5">Pilih zona untuk Masuk Antrian</div>
                @else
                    <div class="block w-full text-center font-body text-xs font-semibold uppercase tracking-[0.1em] bg-surface-2 text-muted rounded-pill py-3.5">Pilih zona untuk Beli Tiket</div>
                @endif
            @else
                <a href="{{route('login')}}" class="block w-full text-center font-body text-sm font-bold uppercase tracking-[0.12em] bg-accent text-ink rounded-pill py-3 hover:bg-cream transition-colors">Login untuk Beli Tiket</a>
            @endauth
        @endif
    </div>

    @if($event->description)<div class="bg-cream mx-4 mb-4 rounded-2xl p-4"><p class="font-body text-[11px] font-semibold text-mid-gray uppercase tracking-[0.06em] mb-1">Tentang Acara</p><p class="font-body text-sm text-ink/70 leading-relaxed">{{$event->description}}</p></div>@endif
</div>
