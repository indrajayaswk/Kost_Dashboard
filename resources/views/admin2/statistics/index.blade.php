<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="container">
                        <h1>{{ $chart->options['chart_title'] }}</h1>
                        {!! $chart->renderHtml() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>

    {!! $chart->renderChartJsLibrary() !!}
    {!! $chart->renderJs() !!}
</x-app-layout>
