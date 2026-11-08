# GUÍA DE REFERENCIA 05: Algoritmo de Optimización en 5 Pasos
**Módulo:** Cálculo Diferencial | **Nivel:** Inicial

---

## Introducción a la Optimización
La optimización es el proceso de encontrar el **mejor resultado posible** bajo ciertas restricciones lógicas (ej. encontrar el área máxima utilizando el menor material posible).

Este proceso se modela mediante el análisis de Puntos Críticos ($Máximos$ y $Mínimos$) usando el cálculo diferencial.

---

## Algoritmo de Solución (Estructura de 5 Pasos)

### Paso 1: Modelar el Problema (Declaración de Variables)
*   Lee el problema e identifica qué es lo que debes **maximizar o minimizar** (Área, Volumen, Costo).
*   Escribe la **Ecuación Principal** que define esto. (Ej. $A = x \cdot y$).

### Paso 2: Reducir a una sola variable (Despeje de Restricciones)
*   Si tu ecuación principal tiene más de una variable ($x$ e $y$), busca el dato extra (restricción) que te da el problema.
*   Despeja una variable de la restricción y sustitúyela en la Ecuación Principal. Tu función debe quedar en términos de una sola variable: $f(x)$.

### Paso 3: Derivar (Encontrar la Tasa de Cambio)
*   Aplica las reglas de derivación sobre tu función final $f(x)$ para obtener $f'(x)$.

### Paso 4: Condición Lógica de Parada (Puntos Críticos)
*   Iguala la primera derivada a cero: **$f'(x) = 0$**.
*   Despeja $x$. En este punto exacto, la pendiente de la función es plana, lo que indica que encontraste la cima de la montaña (Máximo) o el fondo del valle (Mínimo).

### Paso 5: Comprobación Lógica (Test de Segunda Derivada)
*   Deriva por segunda vez tu función para obtener $f''(x)$.
*   Sustituye el valor de $x$ que encontraste en el Paso 4 dentro de $f''(x)$ y observa el signo:
    *   `if (resultado < 0) { Es un MÁXIMO (Cóncava hacia abajo) }`
    *   `if (resultado > 0) { Es un MÍNIMO (Cóncava hacia arriba) }`