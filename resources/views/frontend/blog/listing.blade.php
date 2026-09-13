@extends('frontend.layouts.app')

@php
    $locale = app()->getLocale();
    $isAr = in_array($locale, ['sa', 'ar', 'eg']);
    $isCn = in_array($locale, ['cn', 'zh']);
    $tr = fn ($ar, $en, $cn = null) => $isAr ? $ar : ($isCn && $cn !== null ? $cn : $en);
    $placeholderRect = static_asset('assets/img/placeholder-rect.jpg');
@endphp

@section('content')
    <div class="kn-page">
        <div class="kn-wrap">
            <header class="kn-page-head">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ translate('Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ translate('Blog') }}</li>
                    </ol>
                </nav>
                <div class="kn-post-header-row">
                    <h1 class="kn-page-title">{{ translate('Blogs') }}</h1>
                    <button type="button" class="kn-btn kn-btn-ghost kn-filter-toggle" data-toggle="class-toggle" data-target=".aiz-filter-sidebar">
                        <i class="la la-filter" aria-hidden="true"></i>
                        <span>{{ translate('Filters') }}</span>
                    </button>
                </div>
            </header>

            <div class="kn-blog">
                <!-- Blogs -->
                <div>
                    @if ($blogs->count() > 0)
                        <ul class="kn-blog-grid blog">
                            @foreach ($blogs as $blog)
                                <li>
                                    <article class="kn-post">
                                        <a href="{{ url('blog') . '/' . $blog->slug }}" class="kn-post-media" tabindex="-1" aria-hidden="true">
                                            <img src="{{ $placeholderRect }}"
                                                data-src="{{ uploaded_asset($blog->banner) }}"
                                                alt=""
                                                class="lazyload">
                                        </a>
                                        <div class="kn-post-body">
                                            <div class="kn-post-meta">
                                                <time datetime="{{ date('Y-m-d', strtotime($blog->created_at)) }}">{{ date('M d, Y', strtotime($blog->created_at)) }}</time>
                                                @if ($blog->category != null)
                                                    <span class="kn-post-cat">{{ $blog->category->category_name }}</span>
                                                @endif
                                            </div>
                                            <h2 class="kn-post-title">
                                                <a href="{{ url('blog') . '/' . $blog->slug }}" title="{{ $blog->title }}">{{ $blog->title }}</a>
                                            </h2>
                                            @if ($blog->short_description)
                                                <p class="kn-post-excerpt">{{ $blog->short_description }}</p>
                                            @endif
                                            <a href="{{ url('blog') . '/' . $blog->slug }}" class="kn-post-more" aria-label="{{ translate('Read Full Blog') }}: {{ $blog->title }}">
                                                {{ translate('Read Full Blog') }}
                                                <i class="las la-arrow-right" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </article>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="kn-empty">
                            <i class="las la-newspaper" aria-hidden="true"></i>
                            <p class="kn-text kn-muted">{{ $tr('لا توجد مقالات حالياً.', 'No articles yet.', '暂无文章。') }}</p>
                        </div>
                    @endif

                    <!-- Pagination -->
                    <div class="aiz-pagination">
                        {{ $blogs->links() }}
                    </div>
                </div>

                <!-- Sidebar -->
                <aside class="kn-aside">
                    <!-- Filters -->
                    <form id="search-form" action="" method="GET">
                        <div class="aiz-filter-sidebar collapse-sidebar-wrap sidebar-xl sidebar-right z-1035">
                            <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle" data-target=".aiz-filter-sidebar" data-same=".filter-sidebar-thumb"></div>
                            <div class="collapse-sidebar c-scrollbar-light kn-aside" style="overflow-y: auto;">
                                <div class="d-flex d-xl-none justify-content-between align-items-center px-3 border-bottom">
                                    <h2 class="kn-aside-title m-0">{{ translate('Filters') }}</h2>
                                    <button type="button" class="kn-btn kn-btn-ghost filter-sidebar-thumb" data-toggle="class-toggle" data-target=".aiz-filter-sidebar" aria-label="{{ translate('Close') }}">
                                        <i class="las la-times" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <!-- Search -->
                                <div class="kn-panel">
                                    <label for="kn-blog-search" class="kn-aside-title d-block">{{ translate('Search') }}</label>
                                    <div class="kn-blog-search">
                                        <input type="text" id="kn-blog-search" class="form-control" name="search" value="{{ $search }}" placeholder="{{ translate('Search...') }}" autocomplete="off">
                                        <button class="kn-btn kn-btn-primary" type="submit" aria-label="{{ translate('Search') }}">
                                            <i class="la la-search" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- Categories -->
                                <fieldset class="kn-panel kn-fieldset">
                                    <legend class="kn-aside-title kn-legend">{{ translate('Categories') }}</legend>
                                    <div class="aiz-checkbox-list kn-cat-list">
                                        @foreach (get_all_blog_categories() as $category)
                                            <label class="aiz-checkbox">
                                                <input
                                                    type="checkbox"
                                                    name="selected_categories[]"
                                                    value="{{ $category->slug }}" @if (in_array($category->slug, $selected_categories)) checked @endif
                                                    onchange="filter()"
                                                >
                                                <span class="aiz-square-check"></span>
                                                <span>{{ $category->category_name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </form>

                    <!-- recent posts -->
                    @if ($recent_blogs->count() > 0)
                        <section class="kn-panel" aria-labelledby="kn-recent-title">
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
                        </section>
                    @endif
                </aside>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function filter() {
            $('#search-form').submit();
        }
    </script>
@endsection
