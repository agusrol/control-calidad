import pandas as pd
from sqlalchemy import create_engine
from datetime import datetime

# Leer el Excel
archivo = "Resultados producto (control de calidad).xlsx"
df = pd.read_excel(archivo, sheet_name="2025")

# Renombrar columnas para evitar espacios y caracteres especiales
df.columns = [c.strip().lower().replace(" ", "_").replace(".", "").replace("(", "").replace(")", "").replace("%", "pct").replace("º", "") for c in df.columns]

# Convertir columnas a tipos adecuados
df['fecha'] = pd.to_datetime(df['fecha'], errors='coerce').dt.date  # convertir a fecha

# Convertir numéricos a float (ignorar columnas tipo texto)
for col in df.columns:
    if df[col].dtype == 'object' and col != 'fecha_':
        try:
            df[col] = df[col].str.replace(',', '.').astype(float)
        except:
            pass

# Conexión a PostgreSQL
engine = create_engine("postgresql+psycopg2://postgres:admin@localhost:5432/control_calidad")

# Guardar en PostgreSQL
df.to_sql("control_calidad", engine, if_exists="replace", index=False)
