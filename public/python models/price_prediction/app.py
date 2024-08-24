from flask import Flask, request, jsonify
import joblib
import numpy as np
import pandas as pd

df = pd.read_csv('base dataframe.csv')

app = Flask(__name__)

# Load the trained model
model = joblib.load('model.pkl')


@app.route('/predict', methods=['POST'])
def predict():
    data = request.json

    if not data:
        return jsonify({"error": "No input data found in 'input' key"}), 400

    input_data = pd.DataFrame([data])  # Reshape for a single sample
    #
    final_df = pd.get_dummies(input_data, columns=['make', 'transmission_type', 'driven_wheels'])
    #
    missing_columns = set(df.columns) - set(final_df.columns)
    #
    for col in missing_columns:
        final_df[col] = 0

    final_df = final_df[df.columns]

    final_df.columns = range(len(final_df.columns))

    try:
        # Convert to NumPy array and reshape if necessary
        input_data = np.array(data).reshape(1, -1)
    except Exception as e:
        return jsonify({"error": f"Error processing input data: {str(e)}"}), 400

    try:
        prediction = model.predict(final_df)
    except Exception as e:
        return jsonify({"error": f"Prediction error: {str(e)}"}), 500

    prediction = np.expm1(prediction)

    prediction = prediction.tolist()

    return jsonify({'prediction': prediction})


if __name__ == '__main__':
    app.debug = True
    app.run()
