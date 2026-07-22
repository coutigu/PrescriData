<div class="page-header">
    <div>
        <h1 class="page-title">Calculadora de Hidratação</h1>
        <p class="page-subtitle">Paciente: <strong style="color: var(--primary);"><?= htmlspecialchars($patient['name']) ?></strong> (<?= htmlspecialchars($patient['age']) ?>)</p>
    </div>
    <a href="index.php" class="btn btn-secondary">
        <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i> Voltar
    </a>
</div>

<form id="formHViso" autocomplete="off">
    <input type="hidden" id="patientId" value="<?= htmlspecialchars($patient['id']) ?>">
    <div class="wrapper" style="max-width: 820px; margin: 0 auto; display: flex; flex-direction: column; gap: 20px;">
        <div class="secao">
            <div class="secao-header">Entrada</div>
            <div class="secao-body">
                <div class="grid-2">
                    <div class="campo">
                        <label for="peso">Peso</label>
                        <input type="text" id="peso" name="peso" inputmode="decimal" placeholder="Ex.: 12,5" maxlength="5">
                        <span class="unidade">kg (mínimo 3,000 e máximo 95) · cálculo ao sair do campo</span>
                    </div>
                    <div class="campo">
                        <label for="unidadeTaxa">Taxa</label>
                        <select id="unidadeTaxa" name="unidadeTaxa">
                            <option value="mlh" selected>mL/h</option>
                            <option value="gtt">gotas/min (20 gotas/mL)</option>
                            <option value="microgtt">microgotas/min (60 microgotas/mL)</option>
                        </select>
                        <span class="unidade">A taxa é calculada a partir do volume diário ÷ 24</span>
                    </div>
                </div>

                <div class="grid-2" style="margin-top:14px;">
                    <div class="campo">
                        <label for="nhdPercent">NHD utilizada</label>
                        <select id="nhdPercent" name="nhdPercent">
                            <option value="10">10%</option>
                            <option value="20">20%</option>
                            <option value="30">30%</option>
                            <option value="40">40%</option>
                            <option value="50">50%</option>
                            <option value="60">60%</option>
                            <option value="70">70%</option>
                            <option value="80">80%</option>
                            <option value="90">90%</option>
                            <option value="100" selected>100%</option>
                            <option value="110">110%</option>
                            <option value="120">120%</option>
                            <option value="130">130%</option>
                            <option value="140">140%</option>
                            <option value="150">150%</option>
                            <option value="160">160%</option>
                        </select>
                        <span class="unidade">Necessidades Hídricas Diárias — S.G.5% + NaCl 20% + KCl 10% — de 10 em 10%</span>
                    </div>
                </div>

                <div class="btn-row">
                    <button type="submit" class="btn btn-primary" id="calcularBtn">
                        <i data-lucide="calculator" style="width: 16px; height: 16px;"></i> Calcular e Salvar
                    </button>
                    <button type="button" class="btn btn-secondary d-none" id="imprimirBtn" onclick="window.print();">
                        <i data-lucide="printer" style="width: 16px; height: 16px;"></i> Imprimir Prescrição
                    </button>
                    <button type="reset" class="btn btn-reset">🔄 Limpar</button>
                </div>

                <div id="alerta" class="alerta" role="alert" aria-live="polite"></div>
            </div>
        </div>

        <div class="secao card-minimizable is-minimized" id="standardPreparationCard" data-minimizable-card>
            <button type="button" class="secao-header card-minimizable-header" id="standardPreparationToggle" data-minimizable-toggle aria-expanded="false" aria-controls="standardPreparationBody">
                <span>Solução Isotônica Padrão</span>
                <span class="card-minimizable-arrow" aria-hidden="true">▸</span>
            </button>
            <div class="secao-body card-minimizable-body" id="standardPreparationBody">
                <div class="bloco-padrao">
                    <div class="grid-5">
                        <div class="campo">
                            <label>Número de etapas</label>
                            <input type="text" id="etapasPadrao" readonly>
                            <span class="unidade">total de etapas no dia</span>
                        </div>
                        <div class="campo">
                            <label id="taxaPadraoLabel">mL/hora</label>
                            <input type="text" id="taxaPadrao" class="resultado-verde" readonly>
                            <span class="unidade" id="taxaPadraoInfo">taxa contínua das 24 horas</span>
                        </div>
                        <div class="campo">
                            <label>S.G. 5% por etapa</label>
                            <input type="text" id="sg5Padrao" class="resultado-verde" readonly>
                            <span class="unidade">mL por etapa calculado</span>
                        </div>
                        <div class="campo">
                            <label>NaCl 20% por etapa</label>
                            <input type="text" id="nacl20Padrao" class="resultado-amarelo" readonly>
                            <span class="unidade">mL por etapa calculado</span>
                        </div>
                        <div class="campo">
                            <label>KCl 10% por etapa</label>
                            <input type="text" id="kcl10Padrao" class="resultado-amarelo" readonly>
                            <span class="unidade">mL por etapa calculado</span>
                        </div>
                    </div>
                </div>

                <div class="info-badge">
                    Esta seção mostra a <strong>solução padrão</strong> (500 + 20 + 10), distribuída com o <strong>mesmo número de etapas</strong> e a <strong>mesma taxa de infusão</strong> calculados para o paciente.
                </div>

                <div class="holliday-alert" role="note">
                    <strong>Observação para infusão</strong>
                    A solução padrão representa o <strong>preparo da etapa</strong>. Quando o volume preparado exceder o volume calculado para o paciente, <strong>não infundir obrigatoriamente a etapa inteira</strong>. Respeite o <strong>mL/hora calculado</strong> e o volume total prescrito para 24 horas.
                </div>
            </div>
        </div>


        <div class="secao card-minimizable" id="realPreparationCard" data-minimizable-card>
            <button type="button" class="secao-header card-minimizable-header" id="realPreparationToggle" data-minimizable-toggle aria-expanded="true" aria-controls="realPreparationBody" style="background: var(--primary);">
                <span>Solução Isotônica Personalizada</span>
                <span class="card-minimizable-arrow" aria-hidden="true">▸</span>
            </button>
            <div class="secao-body card-minimizable-body" id="realPreparationBody">
                <div class="bloco-padrao">
                    <div class="grid-5">
                        <div class="campo">
                            <label>Número de etapas</label>
                            <input type="text" id="etapas" readonly>
                            <span class="unidade">total de etapas no dia</span>
                        </div>
                        <div class="campo">
                            <label id="taxaLabel">mL/hora</label>
                            <input type="text" id="taxa" class="resultado-verde" readonly>
                            <span class="unidade" id="taxaInfo">taxa contínua das 24 horas</span>
                        </div>
                        <div class="campo">
                            <label>S.G. 5% por etapa</label>
                            <input type="text" id="sg5Etapa" class="resultado-verde" readonly>
                            <span class="unidade">mL por etapa calculado</span>
                        </div>
                        <div class="campo">
                            <label>NaCl 20% por etapa</label>
                            <input type="text" id="nacl20Etapa" class="resultado-amarelo" readonly>
                            <span class="unidade">mL por etapa calculado</span>
                        </div>
                        <div class="campo">
                            <label>KCl 10% por etapa</label>
                            <input type="text" id="kcl10Etapa" class="resultado-amarelo" readonly>
                            <span class="unidade">mL por etapa calculado</span>
                        </div>
                    </div>
                </div>

                <div class="info-badge">
                    Esta seção mostra o <strong>preparo real calculado por etapa</strong>, derivado do volume diário individual do paciente.
                </div>
            </div>
        </div>

        <div class="secao card-minimizable is-minimized" id="classicHydrationCard" data-minimizable-card>
            <button type="button" class="secao-header card-minimizable-header" id="classicHydrationToggle" data-minimizable-toggle aria-expanded="false" aria-controls="classicHydrationBody">
                <span>Holliday–Segar Clássica</span>
                <span class="card-minimizable-arrow" aria-hidden="true">▸</span>
            </button>
            <div class="secao-body card-minimizable-body" id="classicHydrationBody">
                <div class="bloco-padrao">
                    <div class="grid-5">
                        <div class="campo">
                            <label>Número de etapas</label>
                            <input type="text" id="classicStages" readonly>
                            <span class="unidade">total de etapas no dia</span>
                        </div>
                        <div class="campo">
                            <label id="classicRateLabel">mL/hora</label>
                            <input type="text" id="classicRate" class="resultado-verde" readonly>
                            <span class="unidade" id="classicRateInfo">taxa contínua das 24 horas</span>
                        </div>
                        <div class="campo">
                            <label>S.G. 5% por etapa</label>
                            <input type="text" id="classicSg5Stage" class="resultado-verde" readonly>
                            <span class="unidade">mL por etapa calculado</span>
                        </div>
                        <div class="campo">
                            <label>NaCl 20% por etapa</label>
                            <input type="text" id="classicNacl20Stage" class="resultado-amarelo" readonly>
                            <span class="unidade">mL por etapa calculado</span>
                        </div>
                        <div class="campo">
                            <label>KCl 10% por etapa</label>
                            <input type="text" id="classicKcl10Stage" class="resultado-amarelo" readonly>
                            <span class="unidade">mL por etapa calculado</span>
                        </div>
                    </div>
                </div>

                <div class="holliday-alert" role="note">
                    <strong>Atenção clínica</strong>
                    Alternativa clássica de manutenção baseada em Holliday-Segar, com sódio 3-2-1 mEq/kg/dia e potássio 2-1-0,5 mEq/kg/dia, convertidos em NaCl 20% e KCl 10% por etapa, respeitando teto de 125 mEq/dia para sódio e 67,5 mEq/dia para potássio.
                    Em pacientes pediátricos hospitalizados, considerar que diretrizes atuais geralmente favorecem soluções isotônicas de manutenção, salvo contexto clínico específico.
                </div>
            </div>
        </div>

        <div class="secao">
            <div class="secao-header">Observações</div>
            <div class="secao-body obs">
                <ul>
                    <li>O cálculo do volume diário segue o esquema de Holliday–Segar: 100 mL/kg até 10 kg; 1000 mL + 50 mL/kg entre 10 e 20 kg; 1500 mL + 20 mL/kg acima de 20 kg, com máximo de 3000 mL/dia.</li>
                    <li>A seção <strong>Solução Isotônica Personalizada</strong> exibe o volume real calculado por etapa após aplicar a porcentagem de NHD. As etapas permitidas são 1, 2, 3, 4, 6, 8 e 12, escolhidas conforme necessário para manter o S.G. 5% por etapa sem ultrapassar 500 mL. A seção <strong>Solução Isotônica Padrão</strong> exibe a solução padrão (500 + 20 + 10), mantendo o mesmo número de etapas e a mesma taxa de infusão calculados para o paciente. A aba <strong>Holliday–Segar Clássica</strong> calcula SG 5%, NaCl 20% e KCl 10% pelo esquema clássico com tetos de eletrólitos.</li>
                </ul>
            </div>
        </div>

    </div>
</form>

<?php if (!empty($calculations)): ?>
<div class="panel" style="max-width: 820px; margin: 2rem auto;">
    <div class="panel-header">
        <h2 class="panel-title flex items-center gap-2"><i data-lucide="history"></i> Histórico de Cálculos deste Paciente</h2>
    </div>
    <div class="panel-body" style="padding: 0;">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Peso</th>
                        <th>NHD</th>
                        <th>Taxa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($calculations as $calc): ?>
                        <tr>
                            <td><span class="text-muted"><?= date('d/m/Y H:i', strtotime($calc['created_at'])) ?></span></td>
                            <td style="font-weight: 500;"><?= htmlspecialchars($calc['weight']) ?> kg</td>
                            <td><span class="badge badge-primary"><?= htmlspecialchars($calc['nhd_percent']) ?>%</span></td>
                            <td><?= htmlspecialchars($calc['rate_unit']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="legal-disclaimer">
    <strong>Aviso Legal e Isenção de Responsabilidade:</strong><br>Este software e todas as informações nele contidas são fornecidos exclusivamente com propósitos educacionais e de auxílio à referência médica. ESTE SISTEMA NÃO SUBSTITUI O JULGAMENTO CLÍNICO INDIVIDUAL, A ANAMNESE E O EXAME FÍSICO. As informações aqui apresentadas não devem ser utilizadas isoladamente para o diagnóstico, conduta ou tratamento de pacientes. A responsabilidade final por qualquer decisão clínica, prescrição ou intervenção é exclusiva do profissional de saúde assistente, devidamente habilitado. Os desenvolvedores não garantem a precisão absoluta de todos os dados e isentam-se de responsabilidade por quaisquer danos diretos, indiretos, materiais ou morais que resultem do uso inadequado, interpretação equivocada ou confiança exclusiva nas informações deste sistema. Pacientes e indivíduos leigos que utilizarem este software devem sempre buscar a orientação de um médico qualificado antes de tomar qualquer decisão sobre sua saúde.
</div>
<div class="signature-block"><strong>Desenvolvido por Adão L. L. Couto</strong><br><span class="version-name">1.09.07.16</span></div>

<?php 
$extra_js = '<script>
    // Reveal print button when calculation is complete
    document.getElementById("formHViso").addEventListener("submit", function(e) {
        // Give the AJAX call time to finish, or just show it if validation passes
        setTimeout(() => {
            if (document.getElementById("taxa").value !== "") {
                document.getElementById("imprimirBtn").classList.remove("d-none");
            }
        }, 500);
    });
    document.querySelector(".btn-reset").addEventListener("click", function() {
        document.getElementById("imprimirBtn").classList.add("d-none");
    });
</script>
<script src="assets/js/app.js"></script>';
?>

<style>
/* CSS ORIGINAL DA CALCULADORA BASE */
.wrapper {
  max-width: 820px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: 20px;
}
.secao {
  background: var(--branco, #ffffff);
  border: 1.5px solid var(--borda, #cbd5e1);
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
}
.secao-header {
  background: var(--primary, #0369a1);
  color: #fff;
  padding: 10px 18px;
  font-size: 0.82rem;
  font-weight: 700;
  letter-spacing: 0.8px;
  text-transform: uppercase;
}
.secao-body {
  padding: 18px;
}
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
.grid-5 { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 14px; }
@media (max-width: 760px) {
  .grid-5 { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 640px) {
  .grid-2, .grid-3, .grid-5 { grid-template-columns: 1fr; }
}
.campo { display: flex; flex-direction: column; gap: 4px; }
.campo label {
  font-size: 0.75rem;
  font-weight: 600;
  color: #475569;
  letter-spacing: 0.3px;
}
.campo input, .campo select {
  font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
  font-size: 0.96rem;
  padding: 9px 10px;
  border: 1.5px solid var(--borda, #cbd5e1);
  border-radius: 7px;
  background: var(--cinza, #f1f5f9);
  color: var(--texto, #1e293b);
  width: 100%;
  transition: border-color 0.15s;
}
.campo input:focus, .campo select:focus {
  outline: none;
  border-color: var(--primary, #0369a1);
  background: #fff;
}
.campo input[readonly] {
  background: var(--primary-light, #f0f9ff);
  color: var(--primary, #0369a1);
  font-weight: 600;
  border-color: #93c5fd;
  cursor: default;
}
.campo input.resultado-verde[readonly] {
  background: #dcfce7;
  color: #166534;
  border-color: #86efac;
}
.campo input.resultado-amarelo[readonly] {
  background: #fef9c3;
  color: #854d0e;
  border-color: #fde047;
}
.campo .unidade {
  font-size: 0.7rem;
  color: #94a3b8;
  margin-top: 1px;
}
.btn-row {
  display: flex;
  gap: 10px;
  margin-top: 16px;
  flex-wrap: wrap;
}
.btn-reset {
  background: #f1f5f9;
  color: #64748b;
  border: 1.5px solid var(--borda, #cbd5e1);
}
.btn-reset:hover { background: #e2e8f0; }
.alerta {
  display: none;
  background: var(--vermelho-bg, #fee2e2);
  color: var(--vermelho, #b91c1c);
  border: 1px solid #fecaca;
  border-radius: 8px;
  padding: 10px 12px;
  font-size: 0.85rem;
  margin-top: 14px;
}
.alerta.show {
  display: block;
}
.bloco-padrao {
  border: 1px solid #bfdbfe;
  background: #f8fbff;
  border-radius: 10px;
  padding: 14px;
}
.info-badge {
  font-size: 0.73rem;
  background: #eff6ff;
  border: 1px solid #bfdbfe;
  border-radius: 6px;
  padding: 6px 10px;
  color: #1e40af;
  margin-top: 12px;
  line-height: 1.45;
}
.obs {
  line-height: 1.55;
  color: #475569;
  font-size: 0.86rem;
}
.obs ul { padding-left: 18px; }
.obs li + li { margin-top: 6px; }
.card-minimizable .card-minimizable-header {
  width: 100%;
  border: 0;
  border-radius: 0;
  min-height: 0;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  text-align: left;
  font-family: inherit;
  cursor: pointer;
}
.card-minimizable .card-minimizable-arrow {
  font-size: 1rem;
  line-height: 1;
  transition: transform 0.15s ease;
}
.card-minimizable:not(.is-minimized) .card-minimizable-arrow {
  transform: rotate(90deg);
}
.card-minimizable.is-minimized .card-minimizable-body {
  display: none;
}
.holliday-alert {
  margin-top: 14px;
  background: #fef9c3;
  color: #854d0e;
  border: 1px solid #fde047;
  border-radius: 10px;
  padding: 12px 14px;
  font-size: 0.82rem;
  line-height: 1.45;
}
.holliday-alert strong {
  display: block;
  margin-bottom: 6px;
}
.legal-disclaimer {
  margin: 16px auto 0;
  max-width: 820px;
  font-size: 12px;
  line-height: 1.2;
  color: #5c6f87;
  text-align: justify;
  word-break: break-word;
  background: var(--branco, #ffffff);
  border: 1px solid var(--borda, #cddbea);
  border-radius: 22px;
  box-shadow: 0 16px 34px rgba(18, 44, 73, 0.10);
  padding: 16px;
}
.signature-block {
  max-width: 820px;
  margin: 12px auto 0;
  text-align: center;
  color: #5c6f87;
  font-size: 14px;
  line-height: 1.5;
}

@media print {
  /* Ocultar botões na impressão */
  .btn-row { display: none !important; }
}
</style>
