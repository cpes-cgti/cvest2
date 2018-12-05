
<div class="btn-group">
    <a chave="{{ route('redaction.show', $redaction->id) }}" class="btn btn-default btn-sm btn-exibir" style="color:black"><i class="fas fa-search"></i></a>
    @can('level3')
        <a chave="{{ route('redaction.show_admin', $redaction->id) }}" class="btn btn-default btn-sm btn-exibir" style="color:black"><i class="fas fa-search-plus"></i></a>
    @endcan
    <a chave="{{ route('redaction.details', $redaction->id) }}" class="btn btn-default btn-sm btn-exibir" style="color:black"><i class="fas fa-clipboard-list"></i></a>
</div>
