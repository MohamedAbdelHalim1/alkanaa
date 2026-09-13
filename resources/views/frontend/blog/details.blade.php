@extends('frontend.layouts.app')

@section('meta_title'){{ $blog->meta_title }}@stop

@section('meta_description'){{ $blog->meta_description }}@stop

@section('meta_keywords'){{ $blog->meta_keywords }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $blog->meta_title }}">
    <meta itemprop="description" content="{{ $blog->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($blog->meta_img) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $blog->meta_title }}">
    <meta name="twitter:description" content="{{ $blog->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($blog->meta_img) }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $blog->meta_title }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ route('blog.details', $blog->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($blog->meta_img) }}" />
    <meta property="og:description" content="{{ $blog->meta_description }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
@endsection

@section('content')
    @php
        $placeholderRect = static_asset('assets/img/placeholder-rect.jpg');
    @endphp

    <div class="kn-page">
        <div class="kn-wrap">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ translate('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('blog') }}">{{ translate('Blog') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $blog->title }}</li>
                </ol>
            </nav>

            <div class="kn-post-page">
                <!-- Blog Details -->
                <article class="kn-article">
                    <header class="kn-post-header">
                        @if ($blog->category != null)
                            <div class="kn-post-meta">
                                <span class="kn-post-cat">{{ $blog->category->category_name }}</span>
                            </div>
                        @endif
                        <h1 class="kn-page-title">{{ $blog->title }}</h1>
                        <div class="kn-post-header-row">
                            <div class="kn-post-meta">
                                <time datetime="{{ date('Y-m-d', strtotime($blog->created_at)) }}">{{ date('M d, Y', strtotime($blog->created_at)) }}</time>
                            </div>
                            <!-- Share -->
                            <div class="aiz-share"></div>
                        </div>
                    </header>

                    <!-- Image -->
                    @if ($blog->banner)
                        <figure class="kn-post-banner">
                            <img src="{{ $placeholderRect }}"
                                data-src="{{ uploaded_asset($blog->banner) }}"
                                alt="{{ $blog->title }}"
                                class="lazyload">
                        </figure>
                    @endif

                    <!-- Description -->
                    <div class="kn-prose">
                        {!! $blog->description !!}
                    </div>

                    <!-- Facebook Comment -->
                    @if (get_setting('facebook_comment') == 1)
                        <div class="kn-section">
                            <div class="fb-comments" data-href="{{ route('blog', $blog->slug) }}" data-width="" data-numposts="5"></div>
                        </div>
                    @endif
                </article>

                <!-- recent posts -->
                @if ($recent_blogs->count() > 0)
                    <aside class="kn-panel" aria-labelledby="kn-recent-title">
                        <h2 id="kn-recent-title" class="kn-aside-title">{{ translate('Recent Posts') }}</h2>
                        <ul class="kn-recent">
                            @foreach ($recent_blogs as $recent_blog)
                                <li class="kn-recent-item">
                                    <a href="{{ url('blog') . '/' . $recent_blog->slug }}" class="kn-recent-thumb" tabindex="-1" aria-hidden="true">
                                        <img src="{{ $placeholderRect }}"
                                            data-src="{{ uploaded_asset($recent_blog->banner) }}"
                                            alt=""
                                            class="lazyload">
                                    </a>
                                    <div>
                                        <h3 class="kn-recent-title">
                                            <a href="{{ url('blog') . '/' . $recent_blog->slug }}" title="{{ $recent_blog->title }}">{{ $recent_blog->title }}</a>
                                        </h3>
                                        <div class="kn-post-meta">
                                            <time datetime="{{ date('Y-m-d', strtotime($recent_blog->created_at)) }}">{{ date('M d, Y', strtotime($recent_blog->created_at)) }}</time>
                                            @if ($recent_blog->category != null)
                                                <span class="kn-post-cat">{{ $recent_blog->category->category_name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </aside>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
    @if (get_setting('facebook_comment') == 1)
        <div id="fb-root"></div>
        <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v9.0&appId={{ env('FACEBOOK_APP_ID') }}&autoLogAppEvents=1" nonce="ji6tXwgZ"></script>
    @endif
@endsection
