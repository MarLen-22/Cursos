# GUÍA DE REFERENCIA 04: Árbol de Decisiones para Derivación (Cheat-Sheet)
**Módulo:** Cálculo Diferencial | **Nivel:** Inicial

---

## Nivel 1: Reglas Base (Instrucciones Directas)
Identifica la estructura de la función antes de operar.

*   **¿Es un número solo (Constante)?**
    *   *Regla:* La derivada del reposo absoluto es cero.
    *   *Fórmula:* $\frac{d}{dx}[c] = 0$
*   **¿Es la variable $x$ sola?**
    *   *Regla:* La derivada de $x$ es uno.
    *   *Fórmula:* $\frac{d}{dx}[x] = 1$
*   **¿Es una variable con exponente numérico (Potencia)?**
    *   *Algoritmo:* "Baja el exponente multiplicando al frente y réstale $1$ al exponente original".
    *   *Fórmula:* $\frac{d}{dx}[x^n] = n \cdot x^{n-1}$

---

## Nivel 2: Estructuras Compuestas (Algoritmos Avanzados)
Si la función tiene operaciones entre diferentes términos, sigue estas rutas:

### Ruta A: Sumas y Restas
*   *Instrucción:* Deriva cada término por separado, manteniendo los signos.
*   *Fórmula:* $\frac{d}{dx}[u \pm v] = u' \pm v'$

### Ruta B: Regla del Producto (Multiplicación)
*   *Instrucción:* No puedes derivar ambos a la vez. Turna el proceso.
*   *Algoritmo verbal:* "El primero por la derivada del segundo, MÁS el segundo por la derivada del primero".
*   *Fórmula:* $\frac{d}{dx}[u \cdot v] = u \cdot v' + v \cdot u'$

### Ruta C: Regla del Cociente (División)
*   *Instrucción:* Sigue el orden estricto de arriba ($u$) y abajo ($v$).
*   *Fórmula:* $\frac{d}{dx}\left[\frac{u}{v}\right] = \frac{v \cdot u' - u \cdot v'}{v^2}$

---

## Nivel 3: Funciones Anidadas (Regla de la Cadena)
Utilízala cuando tienes "una función dentro de otra" (Ej. $(2x+3)^4$ o $\sin(x^2)$).

*   *Algoritmo (De afuera hacia adentro):* 
    1. Deriva la función exterior, dejando el "paquete interno" intacto.
    2. Multiplica el resultado por la derivada de lo que estaba adentro.
*   *Fórmula:* $\frac{d}{dx}[f(g(x))] = f'(g(x)) \cdot g'(x)$