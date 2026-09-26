# Git y GitHub

## Identidad local

Comprobar la configuración:

```powershell
git config --global user.name
git config --global user.email
```

Si falta, configurarla con el nombre y correo asociados a GitHub.

## Repositorio local

La Capa 1 deja Git inicializado. Para registrar el primer punto estable:

```powershell
git add .
git commit -m "chore: initialize WordPress project"
```

## Conectar con GitHub mediante HTTPS

Crear un repositorio vacío en GitHub, sin README ni `.gitignore`, y ejecutar:

```powershell
git remote add origin https://github.com/USUARIO/chef-en-casa.git
git branch -M main
git push -u origin main
```

GitHub solicitará autenticación mediante el navegador o Git Credential Manager; no usar la contraseña de la cuenta como contraseña Git.

## Alternativa con GitHub CLI

`gh` no estaba instalado al crear esta capa. Si posteriormente se instala y autentica:

```powershell
gh auth login
gh repo create chef-en-casa --private --source . --remote origin --push
```

No guardar tokens, claves SSH ni el archivo `.env` en el repositorio.

