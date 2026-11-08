# GUÍA DE REFERENCIA 02: Algoritmo Lógico para Límites
**Módulo:** Cálculo Diferencial | **Nivel:** Inicial

---

## Introducción al Límite (Ciclo de Aproximación)
El límite responde a una pregunta de predicción lógica: *¿A qué valor se aproxima mi Output ($y$) cuando mi Input ($x$) se acerca infinitamente a un valor determinado ($a$)?*
Notación: $\lim_{x \to a} f(x) = L$

---

## Diagrama de Flujo para Evaluación de Límites

**PASO 1: Sustitución Directa (Ejecución de Prueba)**
*   Ingresa el valor de $a$ directamente en las $x$ de la función.

**PASO 2: Evaluación del Resultado (Estructura de Decisión)**
*   🔹 **SI el resultado es un número real ($L$):**
    *   **¡ÉXITO!** El ciclo termina. El límite es $L$.
*   🔹 **SI el resultado es $\frac{Número}{0}$ (Infinito):**
    *   **ERROR DETECTADO.** El límite no existe de forma finita (hay una asíntota vertical). Analizar Límites Laterales.
*   🔹 **SI el resultado es $\frac{0}{0}$ (Indeterminación):**
    *   **FALLO LÓGICO.** La función está enmascarada. Pasar al PASO 3.

**PASO 3: Ciclo de Reparación (Solo para Indeterminaciones $\frac{0}{0}$)**
*   Aplica herramientas algebraicas para reescribir el código de la función:
    1.  **Factorización:** Extrae el factor común, diferencia de cuadrados o trinomios.
    2.  **Racionalización:** Si hay raíces, multiplica por el conjugado.
    3.  **Simplificación Algebraica:** Cancela términos idénticos en el numerador y denominador.
*   **Retorno:** Vuelve a ejecutar el **PASO 1** con la nueva función simplificada.

---

## 3. Límites Laterales (Comprobación de Existencia)
Para que un límite exista en un punto $a$, el algoritmo debe acercarse al mismo resultado tanto por la izquierda como por la derecha.
*   Límite por la Izquierda: $\lim_{x \to a^-} f(x)$
*   Límite por la Derecha: $\lim_{x \to a^+} f(x)$

**Condición final:** `if (LimiteIzquierdo == LimiteDerecho) { Limite Existe } else { Diverge }`