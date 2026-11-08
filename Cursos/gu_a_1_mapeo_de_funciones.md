# GUÍA DE REFERENCIA 01: Mapeo Lógico de Funciones (Cajas Negras)
**Módulo:** Cálculo Diferencial | **Nivel:** Inicial

---

## 1. La Función como un Algoritmo (La Máquina)
En programación, una función matemática $f(x)$ funciona exactamente igual que un método o bloque de código que procesa datos:

*   **Input (Entrada):** Es el valor inicial que ingresas al sistema. Representa a la **Variable Independiente** ($x$) y conforma el **Dominio**.
*   **Proceso (La Caja Negra):** Es la regla de correspondencia o la "lógica" que transforma la entrada (ej. elevar al cuadrado, multiplicar, sumar).
*   **Output (Salida):** Es el resultado devuelto por el sistema. Representa a la **Variable Dependiente** ($y$ o $f(x)$) y conforma el **Rango**.

**Visualización Lógica:**
`[ Input: x ]` $\longrightarrow$ `[ Función: f(x) ]` $\longrightarrow$ `[ Output: y ]`

---

## 2. Restricciones Lógicas (Condicionales del Dominio)
Al igual que en un programa informático, existen operaciones matemáticas que generan un "Error de Sintaxis" o "Crash". Al modelar funciones, debemos establecer condiciones lógicas (`if/else`) para evitar estos errores críticos:

| Restricción Matemática | Equivalente en Lógica (Condición de Paro) | Ejemplo Visual |
| :--- | :--- | :--- |
| **División entre Cero** | `if (denominador == 0) { ERROR }` | En $f(x) = \frac{1}{x-2}$, la restricción lógica es $x \neq 2$. |
| **Raíces Pares Negativas**| `if (radicando < 0) { ERROR }` | En $f(x) = \sqrt{x+5}$, el sistema solo acepta $x \ge -5$. |
| **Logaritmos de $\le 0$** | `if (argumento <= 0) { ERROR }`| En $f(x) = \ln(x)$, la entrada debe ser estrictamente $x > 0$. |

---

## 3. Clasificación de Funciones (Tipos de Datos)
*   **Algebraicas:** Operaciones básicas (Polinomiales, Racionales, Radicales).
*   **Trascendentes:** Trascienden el álgebra básica (Trigonométricas, Exponenciales, Logarítmicas).