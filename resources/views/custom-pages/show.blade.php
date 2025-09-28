<div class="mx-auto container mt-4">
    @if(!empty($page->htmlcontent))
        {!! $page->htmlcontent !!}
    @else
        <div class="bg-background-secondary hover:bg-background-secondary/80 border border-neutral p-4 rounded-lg">
            <article class="prose dark:prose-invert mb-2 max-w-full">
                {!! \Illuminate\Support\Str::markdown($page->content) !!}
            </article>
        </div>
    @endif
</div>
