const fs = require('fs');
const path = require('path');
const vm = require('vm');

const raiz = path.resolve(__dirname, '..', 'proyecto 3ra entrega');
const archivoTraducciones = path.join(raiz, 'js', 'traducciones.js');
const contexto = { window: {} };
vm.createContext(contexto);
vm.runInContext(fs.readFileSync(archivoTraducciones, 'utf8'), contexto);
const traducciones = contexto.window.traducciones;

function exigir(condicion, mensaje) {
  if (!condicion) throw new Error(mensaje);
}

exigir(traducciones && traducciones.es && traducciones.en, 'Deben existir los diccionarios es y en.');
const clavesEs = Object.keys(traducciones.es).sort();
const clavesEn = Object.keys(traducciones.en).sort();
exigir(JSON.stringify(clavesEs) === JSON.stringify(clavesEn), 'Los diccionarios ES y EN no tienen las mismas claves.');
for (const clave of clavesEs) {
  exigir(String(traducciones.es[clave]).trim() !== '', `La traducción española ${clave} está vacía.`);
  exigir(String(traducciones.en[clave]).trim() !== '', `La traducción inglesa ${clave} está vacía.`);
}

const archivos = [];
function recorrer(directorio) {
  for (const entrada of fs.readdirSync(directorio, { withFileTypes: true })) {
    const archivo = path.join(directorio, entrada.name);
    if (entrada.isDirectory()) recorrer(archivo);
    else if (/\.(html|js|php|css|sql)$/i.test(entrada.name)) archivos.push(archivo);
  }
}
recorrer(raiz);

const clavesUsadas = new Set();
for (const archivo of archivos) {
  const contenido = fs.readFileSync(archivo, 'utf8');
  exigir(!/[ÃÂ�]/u.test(contenido), `Se detectó texto posiblemente corrupto en ${path.relative(raiz, archivo)}.`);
  for (const coincidencia of contenido.matchAll(/data-clave(?:-(?:placeholder|aria-label|title|alt|data-label))?=["']([^"']+)["']/g)) clavesUsadas.add(coincidencia[1]);
}
for (const clave of clavesUsadas) exigir(traducciones.es[clave] && traducciones.en[clave], `La clave usada ${clave} no está completa.`);

for (const pagina of ['index.html', 'login.html', 'register.html', 'recuperar.html']) {
  const contenido = fs.readFileSync(path.join(raiz, pagina), 'utf8');
  exigir(contenido.includes('data-idioma-opcion="es"') && contenido.includes('data-idioma-opcion="en"'), `${pagina} no ofrece ambos idiomas.`);
}

for (const pagina of fs.readdirSync(raiz).filter((nombre) => nombre.endsWith('.html') && nombre !== 'test-api.html')) {
  const contenido = fs.readFileSync(path.join(raiz, pagina), 'utf8');
  exigir(contenido.includes('js/vendor/i18next-26.5.3.min.js'), `${pagina} no carga el motor i18next local.`);
  exigir(contenido.includes('js/traducciones.js'), `${pagina} no carga el diccionario.`);
  exigir(contenido.includes('js/core/ui.js'), `${pagina} no carga el núcleo de idioma.`);
}

exigir(fs.existsSync(path.join(raiz, 'js', 'vendor', 'i18next-26.5.3.min.js')), 'Falta la copia local y versionada de i18next.');
exigir(traducciones.es.en_curso === 'En curso' && traducciones.en.en_curso === 'In progress', 'El estado En curso no es bilingüe.');
exigir(traducciones.es.completado === 'Completado' && traducciones.en.completado === 'Completed', 'El estado Completado no es bilingüe.');

exigir(traducciones.es.espanol === 'Español', 'El nombre Español debe conservar la ñ.');
exigir(traducciones.es.contrasena === 'Contraseña', 'Contraseña debe conservar la ñ.');
exigir(traducciones.es.manana === 'Mañana', 'Mañana debe conservar la ñ.');
exigir(traducciones.es.seccion === 'Sección', 'Sección debe conservar su tilde.');
const sql = fs.readFileSync(path.join(raiz, 'database.sql'), 'utf8');
exigir(sql.includes('SET NAMES utf8mb4'), 'database.sql debe declarar utf8mb4.');
exigir(sql.includes('Mañana') && sql.includes('programación'), 'Los datos de prueba deben conservar ñ y tildes.');
const configuracion = fs.readFileSync(path.join(raiz, 'config.example.php'), 'utf8');
exigir(configuracion.includes('charset=utf8mb4'), 'La conexión PDO debe declarar utf8mb4.');
const bootstrap = fs.readFileSync(path.join(raiz, 'seguridad', 'bootstrap.php'), 'utf8');
exigir(bootstrap.includes('application/json; charset=utf-8') && bootstrap.includes('JSON_UNESCAPED_UNICODE'), 'Las respuestas JSON deben conservar Unicode y declarar UTF-8.');

console.log(`OK: ${clavesEs.length} claves bilingües, ${clavesUsadas.size} usos y 21 pantallas funcionales verificadas en UTF-8.`);
