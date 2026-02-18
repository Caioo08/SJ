<?php
setlocale(LC_TIME, 'pt_BR.UTF-8', 'pt_BR', 'Portuguese_Brazil');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Procuração - <?= htmlspecialchars($cliente['nome']) ?></title>
<style>
@media print {
    body {
        margin: 0;
        padding: 30mm 20mm 35mm 20mm;
    }

    .btn-group {
        display: none !important;
    }
}

body {
    font-family: 'Times New Roman', Times, serif;
    max-width: 210mm;
    margin: 0 auto;
    padding: 40px;
    background: #fff;
    color: #000;
    line-height: 1.8;
}

.header {
    text-align: center;
    margin-bottom: 40px;
}

.header h1 {
    font-size: 24px;
    font-weight: bold;
    margin: 0;
    text-transform: uppercase;
}

.content {
    text-align: justify;
    font-size: 14px;
    text-indent: 50px;
}

.content p {
    margin: 20px 0;
}

.assinatura {
    margin-top: 80px;
    text-align: center;
}

.linha-assinatura {
    border-top: 1px solid #000;
    width: 300px;
    margin: 0 auto;
    padding-top: 5px;
}

.btn-group {
    position: fixed;
    top: 20px;
    right: 20px;
    display: flex;
    gap: 10px;
}

.btn {
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    cursor: pointer;
    border: none;
    font-size: 14px;
    font-family: Arial, sans-serif;
}

.btn-primary {
    background: #d4af37;
    color: #0b0b0b;
}

.btn-secondary {
    background: #4a9eff;
    color: white;
}

.btn:hover {
    filter: brightness(0.9);
}

@media (max-width: 768px) {
    body {
        padding: 20px;
    }
    
    .btn-group {
        position: static;
        margin-bottom: 20px;
    }
}
.header-doc {
    text-align: center;
    margin-bottom: 40px;
}

.header-nome {
    font-size: 18px;
    font-weight: bold;
    letter-spacing: 1px;
}

.header-oab {
    font-size: 13px;
    margin-top: 4px;
}
.footer-doc {
    position: fixed;
    bottom: 20mm;
    left: 0;
    right: 0;
    text-align: center;
    font-size: 11px;
    color: #000;
}

</style>
</head>
<body>

<div class="btn-group no-print">
    <button onclick="window.close()" class="btn btn-secondary">❌ Fechar</button>
    <button onclick="window.print()" class="btn btn-primary">🖨️ Imprimir</button>
</div>

<div class="header-doc">
    <div class="header-nome">
        WELLINGTON A. PRUDENCIATO
    </div>
    <div class="header-oab">
        OAB 242.243/SP
    </div>
</div>

<div class="header">
    <h1>Procuração</h1>
</div>

<div class="content">
    <p>
        <strong><?= strtoupper(htmlspecialchars($cliente['nome'])) ?></strong>, 
        <?= $cliente['nacionalidade'] ? htmlspecialchars(strtolower($cliente['nacionalidade'])) : 'brasileiro(a)' ?>, 
        <?php
        $estados_civis = [
            'solteiro' => 'solteiro(a)',
            'casado' => 'casado(a)',
            'divorciado' => 'divorciado(a)',
            'viuvo' => 'viúvo(a)',
            'uniao_estavel' => 'em união estável'
        ];
        echo $cliente['estado_civil'] ? $estados_civis[$cliente['estado_civil']] : '____________';
        ?>, 
        <?= $cliente['cpf_cnpj'] ? 'inscrito sob CPF ' . htmlspecialchars($cliente['cpf_cnpj']) : '____________' ?>, 
        <?= $cliente['rg'] ? 'RG ' . htmlspecialchars($cliente['rg']) : '____________' ?>, 
        residente e domiciliado 
        <?php if($cliente['endereco']): ?>
            <?= htmlspecialchars($cliente['endereco']) ?> 
            nº <?= htmlspecialchars($cliente['numero'] ?: 's/n') ?><?= $cliente['complemento'] ? ', ' . htmlspecialchars($cliente['complemento']) : '' ?>, 
            <?= $cliente['bairro'] ? htmlspecialchars($cliente['bairro']) . ', ' : '' ?>
            no Município de <?= htmlspecialchars($cliente['cidade'] ?: '____________') ?>, 
            estado de <?= htmlspecialchars($cliente['uf'] ?: '____________') ?>, 
            <?= $cliente['cep'] ? 'CEP ' . htmlspecialchars($cliente['cep']) : '' ?>
        <?php else: ?>
            à ____________________________________________
        <?php endif; ?>, 
        vem pelo presente instrumento particular de mandato, nomeia e constitui seu bastante e legítimo procurador, 
        <strong><?= strtoupper(htmlspecialchars($advogado['nome'])) ?></strong>, 
        <?= htmlspecialchars($advogado['nacionalidade'] ?: '____________') ?>, <?= htmlspecialchars($advogado['estado_civil'] ?: '__') ?>,
        advogado, regularmente inscrito na OAB/<?= htmlspecialchars($advogado['uf_sigla']) ?> 
        sob o nº <?= htmlspecialchars($advogado['oab']) ?>, 
        com escritório 
        <?php if($advogado['escritorio_endereco']): ?>
            na cidade de <?= htmlspecialchars($advogado['escritorio_cidade'] ?: '____________') ?>/<?= htmlspecialchars($advogado['escritorio_uf'] ?: '__') ?>, 
            <?= htmlspecialchars($advogado['escritorio_endereco']) ?> 
            nº <?= htmlspecialchars($advogado['escritorio_numero'] ?: '___') ?>
            <?= $advogado['escritorio_complemento'] ? ' ' . htmlspecialchars($advogado['escritorio_complemento']) : '' ?> 
            <?= $advogado['escritorio_bairro'] ? htmlspecialchars($advogado['escritorio_bairro']) . ' - ' : '' ?>
            <?= $advogado['escritorio_cep'] ? 'CEP ' . htmlspecialchars($advogado['escritorio_cep']) : '' ?>
        <?php else: ?>
            na cidade de ____________/__, ____________________________________________
        <?php endif; ?>, 
        ao qual confere os poderes da presente procuração para o foro em geral, com a cláusula <strong>"AD JUDICIA ET EXTRA"</strong>, 
        para agir em nome do outorgante, independentemente da ordem de nomeação, junto a Tribunais, Delegacias, CDHU, Autarquias, Fundações, 
        com amplos e ilimitados poderes, para proporem as ações competentes e defendê-la nas contrárias, seguindo umas e outras até final decisão, 
        usando recursos legais, desistindo ou dispensando-os, podendo mais, desistir de ações, renunciar a quaisquer direitos, 
        bem como renunciar ao direito sobre o qual se fundam as ações, acordar, concordar, discordar, transigir, confessar, 
        reconhecer a procedência do pedido, enfim, tudo o mais que se tornar necessário e/ou útil ao bom e fiel desempenho deste mandato, 
        podendo nomear preposto, inclusive podendo substabelecer, com ou sem reserva de poderes, e em especial para promover ação e 
        representá-la em ou qualquer juízo ou instância.
    </p>

    <p style="text-align: right; margin-top: 60px;">
        <?= $cliente['cidade'] ? htmlspecialchars($cliente['cidade']) : '____________' ?>, 
        <?= date('d') ?> de <?= strftime('%B', strtotime('today')) ?> de <?= date('Y') ?>.
    </p>
</div>

<div class="assinatura">
    <div style="margin-top: 100px;">
        <div class="linha-assinatura">
            <?= strtoupper(htmlspecialchars($cliente['nome'])) ?>
        </div>
    </div>
</div>

<div class="footer-doc">
    Fone (014) 98116-1590 &nbsp; | &nbsp;
    E-mail: prudenciato@gmail.com
</div>

<script>
// Configurar idioma para mês em português
const meses = ['janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 
               'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];
const data = new Date();
const mesAtual = meses[data.getMonth()];

// Substituir o mês na data
document.querySelectorAll('p').forEach(p => {
    if (p.textContent.includes('de strftime')) {
        const dia = data.getDate();
        const ano = data.getFullYear();
        const cidade = "<?= $cliente['cidade'] ? htmlspecialchars($cliente['cidade']) : '____________' ?>";
        p.innerHTML = `${cidade}, ${dia} de ${mesAtual} de ${ano}.`;
    }
});
</script>

</body>
</html>