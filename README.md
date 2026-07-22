# HViso Pro - Gestão de Hidratação Venosa Pediátrica

O **HViso Pro** é um SaaS/CRM médico desenvolvido para o cálculo preciso e armazenamento epidemiológico de hidratação venosa isotônica pediátrica, visando segurança, auditoria clínica e adequação às leis de proteção de dados (LGPD).

> **Aviso de Autoria:** O motor lógico (Calculadora de Hidratação em PHP) que compõe o núcleo funcional de cálculos deste sistema foi originalmente criado por **Adão L. L. Couto** 'https://github.com/adaocouto/hviso'. Este sistema web é uma evolução e adaptação arquitetural robusta construída em cima dessa base fundamental.

---

## 🚀 Funcionalidades Implementadas

A partir do script base da calculadora, o sistema foi expandido para uma plataforma profissional contemplando as seguintes camadas:

1. **Arquitetura MVC & Banco de Dados (SQLite):**
   - Transição para o padrão *Model-View-Controller* para escalabilidade.
   - Implementação de um banco de dados relacional embarcado (SQLite), eliminando a necessidade de servidores MySQL pesados.

2. **Autenticação e Perfis de Acesso (RBAC):**
   - Sistema seguro de login com senhas criptografadas (Hash).
   - Divisão hierárquica entre `Usuários` (médicos da ponta) e `Administradores` (gestão).

3. **Gestão de Pacientes (CRM):**
   - Cadastro, Edição e Exclusão de pacientes.
   - Amarração do cálculo da calculadora ao perfil e prontuário do paciente específico.
   - Layout responsivo adaptado para dispositivos móveis (Celulares e Tablets).

4. **Painel de Estatísticas e Dashboard:**
   - Integração com `Chart.js` para visualizar o perfil epidemiológico.
   - Gráficos por faixa etária, sexo, pesos prevalentes e escolhas terapêuticas (Volume/NHD).

5. **Auditoria Global de Resultados:**
   - Módulo restrito para administradores.
   - Tabela de log centralizada mostrando *quem* calculou, *quando* calculou, para *qual paciente* e o *resultado* (Volume Total e NHD).

6. **Adequação à LGPD e Segurança (Hardening):**
   - Bloqueio via `.htaccess` para proteção de pastas sensíveis (especialmente `/db`).
   - Sessões seguras em PHP (HttpOnly, mitigação de XSS e Session Hijacking).
   - Termo de Consentimento Eletrônico obrigatório no cadastro de pacientes (LGPD).
   - "Direito ao Esquecimento": *Hard Delete* completo dos dados de cálculos associados caso o paciente solicite a exclusão.

7. **Impressão de Prescrição:**
   - Visualização isolada (limpeza de menu e botões) utilizando `@media print` CSS.
   - Formatação limpa de ficha clínica pronta para anexar em prontuário físico.

## **Sistema compatível com navegadores móveis:**
   

<table>
  <tr>
    <td><img alt="Screenshot_1" src="https://github.com/user-attachments/assets/12020839-54d3-400f-ab57-55d00303f558" /></td>
    <td><img alt="Screenshot_2" src="https://github.com/user-attachments/assets/79ba50ad-5e34-411a-be94-18117fe28d08" /></td>
    <td><img alt="Screenshot_3" src="https://github.com/user-attachments/assets/345a3bbf-35f3-4218-8dda-cedc228a78a5" /></td>
    <td><img alt="Screenshot_4" src="https://github.com/user-attachments/assets/29e4a047-01cd-43b9-bcc1-d06c623402fe" /></td>
  </tr>
</table>



---

## 🛠️ Tutorial de Instalação

A filosofia do HViso Pro é ser de implantação rápida. Não é necessário configurar um banco de dados MySQL externo, pois ele utiliza SQLite que já vem habilitado por padrão em 99% das hospedagens PHP.

### Pré-requisitos
- Servidor Web (Apache recomendado, para suportar o `.htaccess`).
- PHP 7.4, 8.0, 8.1 ou 8.2.
- Extensão `pdo_sqlite` habilitada no seu `php.ini` (geralmente ativa por padrão).

### Passos para Deploy
1. **Transferência de Arquivos:** Suba os arquivos desta pasta para o diretório público do seu servidor web (ex: `/var/www/html` ou `public_html` via FTP). Ou utilize git clone https://github.com/coutigu/hviso-Pro-APP.git
2. **Permissões de Escrita:** O PHP precisa ter permissão de escrita e leitura na pasta `/db` para conseguir criar e alterar o arquivo `database.sqlite`. 
   - No Linux, você pode rodar: `chmod -R 775 db/` e garantir que o usuário do servidor web (ex: `www-data`) seja o proprietário.
3. **Acesse o Sistema:** Acesse a URL do seu sistema pelo navegador (ex: `http://localhost/hviso-pro` ou `https://seusite.com.br`).

### Primeiro Acesso
No momento do primeiro acesso, se a pasta `/db` não contiver o banco de dados, o sistema irá gerar as tabelas automaticamente.
- O usuário inicial **Administrador** será criado por padrão:
  - **Login:** `admin`
  - **Senha:** `admin123`
- **IMPORTANTE:** Logo após fazer login com este usuário, vá em *Meu Perfil* no menu esquerdo e altere a senha imediatamente. Depois, em *Gestão de Usuários*, cadastre sua equipe.

---
*Este software é fornecido exclusivamente com propósitos educacionais e de auxílio à referência médica. A responsabilidade final por qualquer decisão clínica é exclusiva do profissional de saúde assistente.*
