<div class="card mb-2 mt-4">
    <div class="card-header">
        <a href="#">
            {{ $reply->owner->name }}
        </a> said this {{ $reply->created_at->diffForHumans() }}...
    </div>
    <div class="card-body">
        {{ $reply->body }}
    </div>
</div>
