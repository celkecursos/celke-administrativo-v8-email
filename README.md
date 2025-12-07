Sistema de e-mail para a Celke criado com Laravel 12. Projeto email/....

## Requisitos

* PHP 8.2 ou superior - Conferir a versão: php -v
* MySQL 8.0 ou superior - Conferir a versão: mysql --version
* Composer - Conferir a instalação: composer --version
* Node.js 22 ou superior - Conferir a versão: node -v
* GIT - Conferir se está instalado o GIT: git -v 

## Como rodar o projeto baixado

- Duplicar o arquivo ".env.example" e renomear para ".env".
- Alterar as credenciais do banco de dados.
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=celkev8
DB_USERNAME=root
DB_PASSWORD=
```

- Para a funcionalidade enviar e-mail funcionar, necessário alterar as credenciais do servidor de envio de e-mail no arquivo .env.
- Utilizar o servidor fake durante o desenvolvimento: [Acessar envio gratuito de e-mail](https://mailtrap.io?ref=celke)
```
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=nome-do-usuario-na-mailtrap
MAIL_PASSWORD=senha-do-usuario-na-mailtrap
MAIL_FROM_ADDRESS="colocar-email-remetente@meu-dominio.com.br"
MAIL_FROM_NAME="${APP_NAME}"
```

Instalar as dependências do PHP.
```
composer install
```

Instalar as dependências do Node.js.
```
npm install
```

Gerar a chave no arquivo .env.
```
php artisan key:generate
```

Executar as migrations para criar as tabelas e as colunas.
```
php artisan migrate
```

Executar seed com php artisan para cadastrar registros de testes.
```
php artisan db:seed
```

Iniciar o projeto criado com Laravel.
```
php artisan serve
```

Iniciar o projeto criado com Laravel na porta específica.
```
php artisan serve --port=8084
```

Executar as bibliotecas Node.js.
```
npm run dev
```

Executar os Jobs no PC local.
```
php artisan queue:work
```

Acessar a página criada com Laravel.
```
http://127.0.0.1:8084
```

## Configuração S3/MinIO

### Desenvolvimento Local (MinIO)

Para que as imagens apareçam corretamente no sistema administrativo, configure o MinIO:

```bash
# Dar permissão de execução
chmod +x scripts/dev-setup-minio.sh

# Executar configuração (apenas uma vez)
./scripts/dev-setup-minio.sh
```

**Importante:** Execute apenas após ter o MinIO rodando no Docker.

### Produção (AWS S3)

Para configuração em produção, consulte: [scripts/production-s3-setup.md](./scripts/production-s3-setup.md)

### Mais informações

Consulte a documentação completa em: [scripts/README.md](./scripts/README.md)

## Deploy do projeto na VPS da Hostinger

## Conectar o PC ao servidor com SSH

Criar chave SSH (chave pública e privada).
```
ssh-keygen -t rsa -b 4096 -C "seu-email@exemplo.com"
```
```
ssh-keygen -t rsa -b 4096 -C "cesar@celke.com.br"
```

Local que é criado a chave pública.
```
C:\Users\SeuUsuario\.ssh\
```
```
C:\Users\cesar\.ssh/
```

Exibir o conteúdo da chave pública.
```
cat ~/.ssh/id_rsa.pub
```

Acessar o servidor com SSH.
```
ssh usuario-do-servidor@ip-do-servidor-vps
```
```
ssh root@72.60.1.59
```

Usar o terminal conectado ao servidor para listar os arquivo.
```
cd /caminho-dos-arquivos-no-servidor
```
```
cd /home/user/htdocs/srv939001.hstgr.cloud
```

Listar os arquivo.
```
ls
```

Remover os arquivos do servidor.
```
rm -rf /home/user/htdocs/endereco-do-servidor/{*,.*}
```
```
rm -rf /home/user/htdocs/srv939001.hstgr.cloud/{*,.*}
```

## Conectar Servidor ao GitHub

Gerar a chave SSH no servidor.
```
ssh-keygen -t rsa -b 4096 -C "cesar@celke.com.br"
```

Imprimir a chave pública gerada.
```
cat ~/.ssh/id_rsa.pub
```

- No GitHub, vá para Settings (Configurações) do seu repositório ou da sua conta, em seguida, vá para SSH and GPG keys e clique em New SSH key. Cole a chave pública no campo fornecido e salve.

Verificar a conexão com o GitHub.
```
ssh -T git@github.com
```

- Se gerar o erro "The authenticity of host 'github.com (xx.xxx.xx.xxx)' can't be established.". Isso é uma medida de segurança para evitar ataques de "man-in-the-middle". Necessário adicionar a chave do host do GitHub ao arquivo de known_hosts do seu servidor.

Digite yes quando for solicitado.
```
Are you sure you want to continue connecting (yes/no/[fingerprint])? yes
```

Verificar a conexão novamente.
```
ssh -T git@github.com
```

- Mensagem de conexão realizada com sucesso. Hi nome-usuario! You've successfully authenticated, but GitHub does not provide shell access.

Usar o terminal conectado ao servidor. Primeiro acessar o diretório do projeto no servidor.
```
cd /home/user/htdocs/srv939001.hstgr.cloud
```

Baixar os arquivos do GitHub para a VPS.
```
git clone -b <branch_name> <ssh_repository_url> .
```

Duplicar o arquivo ".env.example" e renomear para ".env".
```
cp .env.example .env
```

Abrir o arquivo ".env" e alterar as variaveis de ambiente.
```
nano .env
```

Ctrl + O e enter para salvar.<br>
Ctrl + X para sair.<br>

Alterar o valor das variaveis de ambiente.
```
APP_NAME=Celke
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_TIMEZONE=America/Sao_Paulo
APP_URL=https://srv566492.hstgr.cloud 
```

Comentar as variaveis de conexão com banco de dados para testar o carregamento da aplicação.
```
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=celke
# DB_USERNAME=root
# DB_PASSWORD=
```

Alterar para armazenar as sessões no arquivo "file" para fazer o teste sem o banco de dados.
```
SESSION_DRIVER=file
```

Instalar as dependências do PHP.
```
composer install
```

Quando gerar o erro "sh: 1: vite: not found", necessário instalar o Vite. Executar e Etapa 1, Etapa 2 e Etapa 3.
```
npm install
```

Instalar as dependências do Node.js. Necessário ter o Node.js instalado no servidor. Abaixo tem as orientações como instalar o Node.js no servidor.
```
npm run build
```

Etapa 1 - Verificar se o Vite está instalado.
```
npx vite --version
```

Etapa 2 - Gerar a build. Compilar o código-fonte do projeto.
```
npm run build
```

Etapa 3 - Remover o diretório "node_modules".

Alterar a propriedade do diretório.
```
sudo chown -R user:user /home/celke-leademail/htdocs/leademail.celke.com.br
```

Reiniciar Nginx.
```
sudo systemctl restart nginx
```

Limpar cache.
```
php artisan config:clear
```

Gerar a chave.
```
php artisan key:generate
```

- Criar a base de dados no MySQL.

Alterar as variaveis de conexão com banco de dados.
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=celke
DB_USERNAME=root
DB_PASSWORD=
```

Alterar para armazenar as sessões no banco de dados "file".
```
SESSION_DRIVER=database
```

Executar as migrations para criar as tabelas e as colunas.
```
php artisan migrate
```

Executar seed com php artisan para cadastrar registros de testes.
```
php artisan db:seed
```

Comando que será abordado no decorrer das aulas.
Marcar o diretório como "seguro" para o Git.
```
git config --global --add safe.directory /home/celke-leademail/htdocs/leademail.celke.com.br 
```

## Ativar o JOB no servidor/VPS

Atualizar a lista de pacotes disponíveis.
```
sudo apt update
```

Instalar o Supervisor para manter o worker sempre rodando.
```
sudo apt install supervisor
```

Criar o arquivo de configuração do supervisor.
```
sudo nano /etc/supervisor/conf.d/laravel-worker.conf
```

Configuração do supervisor.
```
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=/usr/bin/php8.2 /home/celke-thanos/htdocs/thanos.celke.com.br/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=celke-thanos
numprocs=1
redirect_stderr=true
stdout_logfile=/home/celke-thanos/htdocs/thanos.celke.com.br/storage/logs/laravel-worker.log
```

Detecta novas configurações do supervisor. Executar sempre após alteração no arquivo laravel-worker.conf.
```
sudo supervisorctl reread
```

Aplicar as mudanças detectadas no reread.
```
sudo supervisorctl update
```

Iniciar o processo laravel-worker.
```
sudo supervisorctl start laravel-worker:*
```

Reiniciar Nginx.
```
sudo systemctl restart nginx
```

Acessar o caminho do projeto.
```
cd /home/celke-leademail/htdocs/leademail.celke.com.br
```

Limpar a fila após deploys que mudam jobs.
```
php artisan queue:clear
```

Reiniciar os workers após alteração no código que envolva jobs ou models.
```
php artisan queue:restart
```

## Instalar o Node.js no servidor.

Atualizar a lista de pacotes disponíveis.
```
sudo apt update
```

Adicionar no repositório o Node.js 22.x.
```
curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
```

Instalar o Node.js. -y automatizar a instalação de pacotes sem solicitar a confirmação manual do usuário.
```
sudo apt install -y nodejs
```

Reiniciar Nginx.
```
sudo systemctl restart nginx
```

Limpar cache.
```
php artisan config:clear
```

Remover o Node.js.
```
sudo apt remove nodejs
```

## Sequência para criar o projeto

Criar o projeto com Laravel
```
composer create-project laravel/laravel .
```

Iniciar o projeto criado com Laravel.
```
php artisan serve
```

Instalar as dependências do Node.js.
```
npm install
```

Executar as bibliotecas Node.js.
```
npm run dev
```

Acessar o conteúdo padrão do Laravel
```
http://127.0.0.1:8000
```

Criar Controller com php artisan.
```
php artisan make:controller NomeController
```
```
php artisan make:controller CoursesController
```

Criar View com php artisan.
```
php artisan make:view diretorio.nome-view
```
```
php artisan make:view courses.index
```

Criar migration com php artisan.
```
php artisan make:migration create_nome_table
```
```
php artisan make:migration create_courses_table
```

Executar as migrations para criar a base de dados e as tabelas.
```
php artisan migrate
```

Criar seed com php artisan para cadastrar registros de testes.
```
php artisan make:seeder NomeSeeder
```
```
php artisan make:seeder UserSeeder
```

Executar seed com php artisan para cadastrar registros de testes.
```
php artisan db:seed
```

Desfazer todas as migrations e executá-las novamente.
```
php artisan migrate:fresh
```

Desfazer todas as migrations, executá-las novamente e rodar as seeds.
```
php artisan migrate:fresh --seed
```

Criar componente
```
php artisan make:component nome --view
```
```
php artisan make:component alert --view
```

Criar o arquivo de Request com validações para o formulário.
```
php artisan make:request NomeRequest
```
```
php artisan make:request UserRequest
```

Traduzir para português [Módulo pt-BR](https://github.com/lucascudo/laravel-pt-BR-localization)

Instalar o pacote de auditoria do Laravel.
```
composer require owen-it/laravel-auditing
```

Publicar a configuração e as migration para auditoria.
```
php artisan vendor:publish --provider "OwenIt\Auditing\AuditingServiceProvider" --tag="config"
```
```
php artisan vendor:publish --provider "OwenIt\Auditing\AuditingServiceProvider" --tag="migrations"
```

Limpar cache de configuração.
```
php artisan config:clear
```

Instalar a dependência de permissão.
```
composer require spatie/laravel-permission
```

Criar as migrations para o sistema de permissão.
```
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

Limpar cache de configuração.
```
php artisan config:clear
```

Executar as migrations do sistema de permissão.
```
php artisan migrate
```

Instalar a biblioteca para apresentar o alerta personalizado.
```
npm install sweetalert2
```

Instalar a biblioteca para gerar PDF.
```
composer require barryvdh/laravel-dompdf
```

Instalar a biblioteca para gerar gráfico.
```
npm install chart.js
```

Instalar o pacote do Flysystem S3.
```
composer require league/flysystem-aws-s3-v3
```

Como criar o arquivo de rotas para API no Laravel 11
```
php artisan install:api
```

Recortar e redimensionar a imagem.
```
composer require intervention/image
```

Instalar o Alpine.js.
```
npm install alpinejs
```

Instalar a biblioteca de ícones.
```
composer require mallardduck/blade-lucide-icons
```

Publicar o config do Lucide.
```
php artisan vendor:publish --tag=blade-lucide-icons-config
```

Deverá gerar.
```
config/blade-lucide-icons.php
```

Ativar o cache no arquivo que for criado.
```
'cache' => true,
```

Adicionar o cache.
```
php artisan icons:cache
php artisan view:cache
php artisan config:cache
```

Para ativar as alterações do arquivo .ENV.
```
php artisan config:clear
```

## Como enviar e baixar os arquivos do GitHub

- Criar o repositório **"curso-laravel-12"** no GitHub.
- Criar o branch **"develop"** no repositório.

Baixar os arquivos do Git.
```
git clone -b <branch_name> <repository_url> .
```

- Colocar o código fonte do projeto no diretório que está trabalhando.

Alterar o Usuário Globalmente (para todos os repositórios).
```
git config --global user.name "SeuNomeDeUsuario"
git config --global user.email "seuemail@exemplo.com"
```

Verificar em qual está branch.
```
git branch 
```

Baixar as atualizações do GitHub.
```
git pull
```

Adicionar todos os arquivos modificados no staging area - área de preparação.
```
git add .
```

commit representa um conjunto de alterações e um ponto específico da história do seu projeto, registra apenas as alterações adicionadas ao índice de preparação.
O comando -m permite que insira a mensagem de commit diretamente na linha de comando.
```
git commit -m "Base projeto"
```

Enviar os commits locais, para um repositório remoto.
```
git push <remote> <branch>
git push origin develop
```

Voltar um ou mais commits. Usar HEAD~2 para voltar dois commits, e assim por diante.
```
git reset --hard HEAD~1
```

Criar nova branch no PC.
```
git checkout -b <branch>
```
```
git checkout -b main
```

Mudar de branch.
```
git switch <branch>
```
```
git switch main
```

Mesclar o histórico de commits de uma branch em outra branch.
```
git merge <branch_name>
```
```
git merge develop
```

Fazer o push das alterações. 
```
git push origin <branch_name>
```
```
git push origin main
```

## Autor

Este projeto foi desenvolvido por [Cesar Szpak](https://github.com/cesarszpak) e está hospedado no repositório da organização [Celke](https://github.com/celkecursos).

## Licença

Este projeto está licenciado sob a licença MIT - veja o arquivo [LICENSE](LICENSE.txt) para mais detalhes.