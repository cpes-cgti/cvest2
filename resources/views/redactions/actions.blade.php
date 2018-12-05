
<div class="btn-group">
        <abbr title="Visualizar a redação"><a chave="{{ route('redaction.show', $redaction->id) }}" class="btn btn-default btn-sm btn-exibir" style="color:black"><i class="fas fa-search"></i></a></abbr>
    @can('level3')
        <abbr title="Visualizar a redação (Acesso restrito)"><a chave="{{ route('redaction.show_admin', $redaction->id) }}" class="btn btn-default btn-sm btn-exibir" style="color:black"><i class="fas fa-search-plus"></i></a></abbr>
    @endcan
    <abbr title="Detalhes"><a chave="{{ route('redaction.details', $redaction->id) }}" class="btn btn-default btn-sm btn-exibir" style="color:black"><i class="fas fa-clipboard-list"></i></a></abbr>
</div>
